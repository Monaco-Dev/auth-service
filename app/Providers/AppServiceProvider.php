<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Services\Contracts\{
    AuthServiceInterface,
    ConnectionInvitationServiceInterface,
    ConnectionServiceInterface,
    UserServiceInterface
};
use App\Services\{
    AuthService,
    ConnectionInvitationService,
    ConnectionService,
    UserService
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        AuthServiceInterface::class => AuthService::class,
        UserServiceInterface::class => UserService::class,
        ConnectionServiceInterface::class => ConnectionService::class,
        ConnectionInvitationServiceInterface::class => ConnectionInvitationService::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
