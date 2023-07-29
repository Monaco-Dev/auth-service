<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserResource;
use App\Notifications\InviteNotification;
use App\Repositories\Contracts\ConnectionInvitationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\ConnectionInvitationServiceInterface;

class ConnectionInvitationService extends Service implements ConnectionInvitationServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\ConnectionInvitationRepositoryInterface
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(
        ConnectionInvitationRepositoryInterface $repository,
        UserRepositoryInterface $userRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function index()
    {
        $data = $this->userRepository->pendingInvitations();

        return UserResource::collection($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests()
    {
        $data = $this->userRepository->requestInvitations();

        return UserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        $this->repository->create([
            'user_id' => Auth::user()->id,
            'invitation_user_id' => Arr::get($request, 'user_id')
        ]);

        $user = $this->userRepository->find(Arr::get($request, 'user_id'));

        $user->notify(new InviteNotification($user));

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        return $this->repository->cancel([
            'invitation_user_id' => $id,
            'user_id' => Auth::user()->id
        ]);
    }
}
