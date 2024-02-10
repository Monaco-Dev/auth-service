<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    BrokerLicenseRepositoryInterface,
    ConnectionInvitationRepositoryInterface,
    ConnectionRepositoryInterface,
    FollowRepositoryInterface,
    SocialiteRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    BrokerLicenseRepository,
    ConnectionInvitationRepository,
    ConnectionRepository,
    FollowRepository,
    SocialiteRepository,
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
        BrokerLicenseRepositoryInterface::class => BrokerLicenseRepository::class,
        ConnectionRepositoryInterface::class => ConnectionRepository::class,
        ConnectionInvitationRepositoryInterface::class => ConnectionInvitationRepository::class,
        FollowRepositoryInterface::class => FollowRepository::class,
        SocialiteRepositoryInterface::class => SocialiteRepository::class,
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
