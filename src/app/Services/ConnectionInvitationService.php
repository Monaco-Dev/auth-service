<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Notifications\SendInviteNotification;
use App\Repositories\Contracts\ConnectionInvitationRepositoryInterface;
use App\Services\Contracts\ConnectionInvitationServiceInterface;

class ConnectionInvitationService extends Service implements ConnectionInvitationServiceInterface
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
     * @param App\Repositories\Contracts\ConnectionInvitationRepositoryInterface
     */
    public function __construct(ConnectionInvitationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function send(array $request, User $user)
    {
        $message = Arr::get($request, 'message');

        $auth = Auth::user();

        $auth->outgoingInvites()->attach($user, ['message' => $message]);

        $user->notify(new SendInviteNotification($auth, $message));

        return new UserResource($user);
    }

    /**
     * Search for specific resources in the database.
     * 
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function searchIncoming(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            Auth::user()
                ->incomingInvites()
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
    public function searchOutgoing(array $request)
    {
        $search = Arr::get($request, 'search');

        return $this->setResponseCollection(
            Auth::user()
                ->outgoingInvites()
                ->search($search, Auth::user()->id)
                ->simplePaginate()
        );
    }
}
