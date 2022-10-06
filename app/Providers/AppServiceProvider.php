<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

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
        if( $this->app->environment('production') ){
            URL::forceScheme('https');
        }

        // JsonResource::withoutWrapping();

        $this->app->singleton('SMS', function ($app) {
            return new \App\Services\SmsManager($app);
        });
        $this->app->singleton('ActivityLog', function ($app) {
            return new \App\Services\ActivityLogManager($app);
        });
        $this->app->singleton('PaymentGateway', function ($app) {
            return new \App\Services\PaymentGatewayManager($app);
        });
    }
}
