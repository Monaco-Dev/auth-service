<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\FollowRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
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
     * @var \App\Repositories\Contracts\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\FollowRepositoryInterface
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(
        FollowRepositoryInterface $repository,
        UserRepositoryInterface $userRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
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

        $user = $this->userRepository->model()
            ->withRelations()
            ->withCount([
                'mutuals' => function ($query) use ($auth) {
                    $query->whereHas('connections', function ($query) use ($auth) {
                        $query->where('connection_user_id', $auth->id);
                    });
                }
            ])
            ->whereId($user->id)
            ->first();

        return response()->json(new UserResource($user));
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

        $user = $this->userRepository->model()
            ->withRelations()
            ->withCount([
                'mutuals' => function ($query) use ($auth) {
                    $query->whereHas('connections', function ($query) use ($auth) {
                        $query->where('connection_user_id', $auth->id);
                    });
                }
            ])
            ->whereId($user->id)
            ->first();

        return response()->json(new UserResource($user));
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
                ->simplePaginate()
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
                ->simplePaginate()
        );
    }
}
