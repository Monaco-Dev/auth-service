<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Exception;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\SocialRepositoryInterface;

class UserService extends Service implements UserServiceInterface
{
    /**
     * Resource class of the service.
     * 
     * @var \App\Http\Resources\UserResource
     */
    protected $resourceClass = UserResource::class;

    /**
     * @var \App\Repositories\Contracts\SocialRepositoryInterface
     */
    protected $socialRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     * @param App\Repositories\Contracts\SocialRepositoryInterface
     */
    public function __construct(
        UserRepositoryInterface $repository,
        SocialRepositoryInterface $socialRepository
    ) {
        $this->repository = $repository;
        $this->socialRepository = $socialRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @param bool $findOrFail
     * @return \Illuminate\Http\Response
     */
    public function show($uuid, bool $findOrFail = true)
    {
        return $this->setResponseResource(
            $this->repository->model()
                ->withRelations()
                ->verified()
                ->whereUuid($uuid)
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

            if ($file && !App::runningUnitTests()) {
                if ($model->avatar) Storage::disk('gcs')->delete($model->avatar);

                $fileName = $model->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs('Avatars', $fileName, 'gcs');

                Arr::set($request, 'avatar', $path);
            }

            $this->socialRepository->model()->whereUserId($model->id)->delete();

            if (Arr::get($request, 'socials')) {
                $request['socials'] = array_map(function ($v) use ($model) {
                    $v['user_id'] = $model->id;
                    $v['created_at'] = now();
                    $v['updated_at'] = now();
                    return $v;
                }, $request['socials']);

                $this->socialRepository->model()->insert($request['socials']);
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
                ->simplePaginate(Arr::get($request, 'limit') ?? 15)
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
                ->simplePaginate(15)
        );
    }
}
