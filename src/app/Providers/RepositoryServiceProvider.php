<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    BrokerLicenseRepositoryInterface,
    ConnectionInvitationRepositoryInterface,
    ConnectionRepositoryInterface,
    FollowRepositoryInterface,
    SlugRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    BrokerLicenseRepository,
    ConnectionInvitationRepository,
    ConnectionRepository,
    FollowRepository,
    SlugRepository,
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
        SlugRepositoryInterface::class => SlugRepository::class,
        ConnectionRepositoryInterface::class => ConnectionRepository::class,
        ConnectionInvitationRepositoryInterface::class => ConnectionInvitationRepository::class,
        FollowRepositoryInterface::class => FollowRepository::class,
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
