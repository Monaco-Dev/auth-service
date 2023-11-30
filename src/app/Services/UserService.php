<?php

namespace App\Services;

use Illuminate\Support\Arr;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;

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
        $this->repository->update($model, $request);

        return $this->setResponseResource(
            $this->repository->model()
                ->withRelations()
                ->verified()
                ->whereId($model->id)
                ->first()
        );
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
                ->paginate()
        );
    }
}
