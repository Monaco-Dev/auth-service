<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\FollowRepositoryInterface;
use App\Services\Contracts\FollowServiceInterface;

class FollowService extends Service implements FollowServiceInterface
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
     * @param App\Repositories\Contracts\FollowRepositoryInterface
     */
    public function __construct(FollowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function follow(User $user)
    {
        $auth = Auth::user();

        $auth->following()->attach($user);

        return response()->json(true, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function unfollow(User $user)
    {
        $auth = Auth::user();

        $auth->following()->detach($user);

        return response()->json(true, 200);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowing(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            Auth::user()
                ->following()
                ->search($search, Auth::user()->id)
                ->paginate()
        );
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchFollowers(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            Auth::user()
                ->followers()
                ->search($search, Auth::user()->id)
                ->paginate()
        );
    }
}
