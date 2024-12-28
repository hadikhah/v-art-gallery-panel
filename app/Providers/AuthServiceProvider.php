<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        RedirectIfAuthenticated::redirectUsing(function ($user) {
            return route("home", ["lang" => config("app.locale")]);
        });


        ResetPassword::createUrlUsing(function ($user, string $token) {
            return route("password.reset", ["token" => $token, "lang" => config("app.locale")]) . "?email=" . $user->email;
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
