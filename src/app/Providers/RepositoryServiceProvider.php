<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\{
    LicenseRepositoryInterface,
    ConnectionInvitationRepositoryInterface,
    ConnectionRepositoryInterface,
    FollowRepositoryInterface,
    SocialiteRepositoryInterface,
    SocialRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    LicenseRepository,
    ConnectionInvitationRepository,
    ConnectionRepository,
    FollowRepository,
    SocialiteRepository,
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
        LicenseRepositoryInterface::class => LicenseRepository::class,
        ConnectionRepositoryInterface::class => ConnectionRepository::class,
        ConnectionInvitationRepositoryInterface::class => ConnectionInvitationRepository::class,
        FollowRepositoryInterface::class => FollowRepository::class,
        SocialiteRepositoryInterface::class => SocialiteRepository::class,
        SocialRepositoryInterface::class => SocialRepository::class,
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
