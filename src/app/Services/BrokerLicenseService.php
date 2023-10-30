<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Contracts\BrokerLicenseRepositoryInterface;
use App\Services\Contracts\BrokerLicenseServiceInterface;

class BrokerLicenseService extends Service implements BrokerLicenseServiceInterface
{
    /**
     * Create the service instance and inject its repository.
     *
     * @param App\Repositories\Contracts\BrokerLicenseRepositoryInterface
     */
    public function __construct(BrokerLicenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(array $request)
    {
        $model = Auth::user()->brokerLicense;

        $model->fill($request);

        if ($model->isDirty()) {
            Arr::set($request, 'verified_at', null);
        }

        return $this->repository->updateOrCreate(
            ['user_id' => $model->user_id],
            $request
        );
    }
}
