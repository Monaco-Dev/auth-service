<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ConnectedNotification;
use App\Repositories\Contracts\ConnectionRepositoryInterface;
use App\Services\Contracts\ConnectionServiceInterface;

class ConnectionService extends Service implements ConnectionServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\ConnectionRepositoryInterface
     */
    public function __construct(ConnectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function connect(User $user)
    {
        $auth = Auth::user();

        $auth->connections()->attach($user);
        $user->connections()->attach($auth);

        $auth->incomingInvites()->detach($user);
        $auth->outgoingInvites()->detach($user);

        $user->incomingInvites()->detach($auth);
        $user->outgoingInvites()->detach($auth);

        $user->notify(new ConnectedNotification($auth));

        return response()->json(true, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function disconnect(User $user)
    {
        $auth = Auth::user();

        $auth->connections()->detach($user);
        $user->connections()->detach($auth);

        $auth->incomingInvites()->detach($user);
        $auth->outgoingInvites()->detach($user);

        $user->incomingInvites()->detach($auth);
        $user->outgoingInvites()->detach($auth);

        return response()->json(true, 200);
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

        return UserResource::collection(
            Auth::user()
                ->connections()
                ->search($search, Auth::user()->id)
                ->paginate()
        );
    }
}
