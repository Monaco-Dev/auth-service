<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;

use Google\Cloud\Storage\StorageClient;

class UserService extends Service implements UserServiceInterface
{
    /**
     * Resource class of the service.
     * 
     * @var \App\Http\Resources\UserResource
     */
    protected $resourceClass = UserResource::class;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @param bool $findOrFail
     * @return \Illuminate\Http\Response
     */
    public function show($slug, bool $findOrFail = true)
    {
        return $this->setResponseResource(
            $this->repository->model()
                ->withRelations()
                ->verified()
                ->whereSlug($slug)
                ->first()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function update(mixed $model, array $request)
    {
        DB::beginTransaction();

        try {
            $file = Arr::get($request, 'avatar');

            if ($file) {
                // if ($model->avatar) Storage::disk('gcs')->delete($model->avatar);
                $storage = new StorageClient();
                $bucket = $storage->bucket('realmate');
                $bucket->upload($file);

                $fileName = $model->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $storeFile = $file->storeAs('Avatars', $fileName, 'gcs');

                Arr::set($request, 'avatar', $storeFile);
            }

            $this->repository->update($model, $request);

            $user = $this->repository->model()
                ->withRelations()
                ->whereId($model->id)
                ->first();

            if (Arr::get($request, 'email')) {
                // authenticate
                $token = $this->repository->authenticate(
                    $user->email,
                    Arr::get($request, 'password')
                );

                $user->token = $token;

                Auth::setUser($user);
            }

            DB::commit();

            return $this->setResponseResource($user);
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            $this->repository
                ->model()
                ->search($search)
                ->paginate(Arr::get($request, 'limit') ?? 15)
        );
    }

    /**
     * Search for specific resources in the database.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMutuals()
    {
        return $this->setResponseCollection(
            $this->repository
                ->model()
                ->searchMutuals()
                ->paginate(15)
        );
    }
}
