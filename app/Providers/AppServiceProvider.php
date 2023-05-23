<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
    public function boot()
    {
        
        // RateLimiter::for('backups', function($job) {
        //     return $job->user->vipCustomer()
        //                 ? Limit::none()
        //                 : Limit::perMinute(1)->by(($job->user->id));
        // });
    }
}
