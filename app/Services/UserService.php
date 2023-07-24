<?php

namespace App\Services;

use Illuminate\Support\Arr;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

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

        if (Arr::has($request, 'email') || Arr::has($request, 'username')) {
            auth()->user()->tokens->each(fn ($token) => $this->repository->logout($token->id));
        }

        return $this->show($id);
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @param bool $findOrFail
     * @return \Illuminate\Http\Response
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
        $search = Arr::get($request, 'search');

        $data = $this->repository->model()
            ->withProfile();

        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            })
                ->orWhereHas('brokerLicense', function ($query) use ($search) {
                    $query->where('license_number', 'like', '%' . $search . '%');
                });
        }

        $data = $data->paginate();

        return UserResource::collection($data);
    }
}
