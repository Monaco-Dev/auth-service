<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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
     * @param int|string $id
     * @return int
     */
    public function destroy($id)
    {
        $this->repository->model()
            ->where('invitation_user_id', $id)
            ->where('user_id', Auth::user()->id)
            ->forceDelete();

        return response()->json(true);
    }
}
