<?php

namespace App\Services;

use App\Repositories\Contracts\SocialRepositoryInterface;
use App\Services\Contracts\SocialServiceInterface;

class SocialService extends Service implements SocialServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\SocialRepositoryInterface
     */
    public function __construct(SocialRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Update or store a newly created resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function sync(array $request)
    {
        $id = auth()->user()->id;

        $request['socials'] = array_map(function ($v) use ($id) {
            $v['user_id'] = $id;
            $v['created_at'] = now();
            $v['updated_at'] = now();
            return $v;
        }, $request['socials']);

        $this->repository->model()->whereUserId($id)->delete();

        $response = $this->repository->model()->insert($request['socials']);

        return response()->json($response);
    }
}
