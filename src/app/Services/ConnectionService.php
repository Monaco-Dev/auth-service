<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ConnectedNotification;
use App\Repositories\Contracts\ConnectionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\ConnectionServiceInterface;

class ConnectionService extends Service implements ConnectionServiceInterface
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
     * @param App\Repositories\Contracts\ConnectionRepositoryInterface
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(
        ConnectionRepositoryInterface $repository,
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
    public function connect(User $user)
    {
        DB::beginTransaction();

        try {
            $auth = Auth::user();

            $auth->connections()->attach($user);
            $user->connections()->attach($auth);

            if (!$user->is_following) {
                $auth->following()->attach($user);
            }

            if (!$user->following()
                ->where('follow_user_id', $auth->id)
                ->exists()) {
                $user->following()->attach($auth);
            }

            $auth->incomingInvites()->detach($user);
            $auth->outgoingInvites()->detach($user);

            $user->incomingInvites()->detach($auth);
            $user->outgoingInvites()->detach($auth);

            $user->notify(new ConnectedNotification($auth));

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

            $response = new UserResource($user);

            DB::commit();

            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function disconnect(User $user)
    {
        DB::beginTransaction();

        try {
            $auth = Auth::user();

            $auth->connections()->detach($user);
            $user->connections()->detach($auth);

            $auth->following()->detach($user);

            $auth->incomingInvites()->detach($user);
            $auth->outgoingInvites()->detach($user);

            $user->incomingInvites()->detach($auth);
            $user->outgoingInvites()->detach($auth);

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

            $response = new UserResource($user);

            DB::commit();

            return response()->json($response);
        } catch (\Exception $e) {
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
            Auth::user()
                ->connections()
                ->search($search, Auth::user()->id)
                ->simplePaginate()
        );
    }
}
