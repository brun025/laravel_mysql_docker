<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Company;
use App\Observers\CompanyObserver;
use App\Observers\UserObserver;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // Force https on URL
        if (config('app.force_https')) {
            $url->forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        // Obervers
        Company::observe(CompanyObserver::class);
        User::observe(UserObserver::class);
    }
}
