<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Policies\ConnectionInvitationPolicy;
use App\Policies\ConnectionPolicy;
use App\Policies\FollowPolicy;
use App\Policies\SlugPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return config('services.web_url') . '/reset-password?token=' . $token . '&email=' . $user->email;
        });

        $this->registerPolicies();

        Gate::define('delete-slug', [SlugPolicy::class, 'deleteSlug']);

        Gate::define('connect', [ConnectionPolicy::class, 'connect']);
        Gate::define('disconnect', [ConnectionPolicy::class, 'disconnect']);

        Gate::define('invite', [ConnectionInvitationPolicy::class, 'invite']);

        Gate::define('follow', [FollowPolicy::class, 'follow']);
        Gate::define('unfollow', [FollowPolicy::class, 'unfollow']);
    }
}
