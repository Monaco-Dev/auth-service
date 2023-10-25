<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\SlugRepositoryInterface;

class UserService extends Service implements UserServiceInterface
{
    /**
     * Resource class of the service.
     * 
     * @var \App\Http\Resources\UserResource
     */
    protected $resourceClass = UserResource::class;

    /**
     * @var \App\Repositories\Contracts\SlugRepositoryInterface
     */
    protected $slugRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\UserRepositoryInterface
     * @param App\Repositories\Contracts\SlugRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $repository, SlugRepositoryInterface $slugRepository)
    {
        $this->repository = $repository;
        $this->slugRepository = $slugRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param string $url
     * @param bool $findOrFail
     * @return \Illuminate\Http\Response
     */
    public function show($url, bool $findOrFail = true)
    {
        return $this->setResponseResource(
            $this->slugRepository->profile($url)
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
                ->search($search, Auth::user()->id)
                ->paginate()
        );
    }
}
