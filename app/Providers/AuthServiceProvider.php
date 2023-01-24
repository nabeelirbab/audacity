<?php

namespace App\Providers;

use App\Impersonate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    // protected $policies = [
    //     'App\Model' => 'App\Policies\ModelPolicy',
    // ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }

    public function register()
    {
        $this->app->singleton('impersonate', function () {
            return $this->app->make(Impersonate::class);
        });
    }
}
