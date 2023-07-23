<?php

namespace App\Services;

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
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $request)
    {
        if (Auth::user()->brokerLicense) $this->repository->forceDelete(Auth::user()->brokerLicense->id);

        return $this->repository->create($request);
    }
}
