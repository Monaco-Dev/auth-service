<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Passport\Passport;

use App\Models\User;
use App\Policies\ConnectionInvitationPolicy;
use App\Policies\ConnectionPolicy;
use App\Policies\FollowPolicy;

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
        Passport::tokensExpireIn(now()->addMinutes(config('auth.access_token_timeout')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.refresh_token_timeout')));

        ResetPassword::toMailUsing(function (object $notifiable, string $url) {
            $url = url(config('services.web_url') . '/reset-password?token=' . $url . '&email=' . urlencode($notifiable->email));

            return (new MailMessage)
                ->subject('Reset Password')
                ->markdown('mail.reset-password', ['url' => $url, 'name' => $notifiable->full_name]);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $url = parse_url($url);
            $path = str_replace('/api/', '', $url['path']);
            $url = config('services.web_url') . '/verify-email?path=' . $path . '&' . $url['query'];

            return (new MailMessage)
                ->subject('Email Verification')
                ->markdown('mail.verify-email', ['url' => url($url), 'name' => $notifiable->full_name]);
        });

        $this->registerPolicies();

        Gate::define('connect', [ConnectionPolicy::class, 'connect']);
        Gate::define('disconnect', [ConnectionPolicy::class, 'disconnect']);

        Gate::define('invite', [ConnectionInvitationPolicy::class, 'invite']);

        Gate::define('follow', [FollowPolicy::class, 'follow']);
        Gate::define('unfollow', [FollowPolicy::class, 'unfollow']);
    }
}
