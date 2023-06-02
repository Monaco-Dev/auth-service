<?php

namespace App\Services;

use Illuminate\Support\Arr;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;

class UserService extends Service implements UserServiceInterface
{
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
     * Update the specified resource in storage.
     *
     * @param int|string $id
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $request)
    {
        if (Arr::has($request, 'email')) Arr::set($request, 'email_verified_at', null);

        if (Arr::has($request, 'phone_number')) Arr::set($request, 'phone_number_verified_at', null);

        $this->repository->update($id, $request);

        if (Arr::has($request, 'email')) $this->repository->find($id)->sendEmailVerificationNotification();

        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id, bool $findOrFail = true)
    {
        $user = $this->repository->model()->withProfile()->find($id);

        return new UserResource($user);
    }

    /**
     * Search for specific resources in the database.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function search(array $request)
    {
        $data = $this->repository->model()
            ->withProfile()
            ->where('username', 'like', '%' . Arr::get($request, 'username') . '%')
            ->paginate();

        return UserResource::collection($data);
    }
}
