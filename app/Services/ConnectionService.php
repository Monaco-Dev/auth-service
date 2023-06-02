<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Notifications\ConnectNotification;
use App\Repositories\Contracts\{
    ConnectionInvitationRepositoryInterface,
    ConnectionRepositoryInterface,
    UserRepositoryInterface
};
use App\Services\Contracts\ConnectionServiceInterface;

class ConnectionService extends Service implements ConnectionServiceInterface
{
    /**
     * @var \App\Repositories\Contracts\ConnectionInvitationRepositoryInterface
     */
    protected $connectionInvitationRepository;

    /**
     * @var \App\Repositories\Contracts\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\ConnectionRepositoryInterface
     * @param App\Repositories\Contracts\ConnectionInvitationRepositoryInterface
     * @param App\Repositories\Contracts\UserRepositoryInterface
     */
    public function __construct(
        ConnectionRepositoryInterface $repository,
        ConnectionInvitationRepositoryInterface $connectionInvitationRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->repository = $repository;
        $this->connectionInvitationRepository = $connectionInvitationRepository;
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
        DB::beginTransaction();

        try {
            $userId = Arr::get($request, 'user_id');
            $authId = Auth::user()->id;

            $this->repository->create([
                'user_id' => $userId,
                'connection_user_id' => $authId
            ]);

            $this->repository->create([
                'user_id' => $authId,
                'connection_user_id' => $userId
            ]);

            $this->connectionInvitationRepository->model()
                ->where('user_id', $userId)
                ->where('invitation_user_id', $authId)
                ->forceDelete();

            $this->userRepository->find($userId)->notify(new ConnectNotification);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

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
            ->where('connection_user_id', $id)
            ->where('user_id', Auth::user()->id)
            ->forceDelete();

        return response()->json(true);
    }
}
