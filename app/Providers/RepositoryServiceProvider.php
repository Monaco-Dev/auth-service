<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    BrokerLicenseRepositoryInterface,
    ConnectionInvitationRepositoryInterface,
    ConnectionRepositoryInterface,
    SocialRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    BrokerLicenseRepository,
    ConnectionInvitationRepository,
    ConnectionRepository,
    SocialRepository,
    UserRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        SocialRepositoryInterface::class => SocialRepository::class,
        BrokerLicenseRepositoryInterface::class => BrokerLicenseRepository::class,
        ConnectionRepositoryInterface::class => ConnectionRepository::class,
        ConnectionInvitationRepositoryInterface::class => ConnectionInvitationRepository::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
