<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

use App\Models\Connection;
use App\Repositories\Contracts\ConnectionRepositoryInterface;

class ConnectionRepository extends Repository implements ConnectionRepositoryInterface
{
    /**
     * Create the repository instance.
     *
     * @param \App\Models\Connection
     */
    public function __construct(Connection $model)
    {
        $this->model = $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function disconnect(array $request)
    {
        return $this->model
            ->where('connection_user_id', Arr::get($request, 'connection_user_id'))
            ->where('user_id', Arr::get($request, 'user_id'))
            ->forceDelete();
    }
}
