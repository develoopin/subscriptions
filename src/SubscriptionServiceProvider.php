<?php

namespace Develoopin\Subscription;

use Illuminate\Support\ServiceProvider;
use Develoopin\Subscription;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/subscriptions.php' => config_path('laraplans.php')
        ], 'config');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laraplans.php', 'laraplans');

        $this->app->bind(FeaturesInterface::class, config('subscriptions.models.features'));
        $this->app->bind(ModulsInterface::class, config('subscriptions.models.moduls'));
        $this->app->bind(PlansInterface::class, config('subscriptions.models.plans'));
        $this->app->bind(SubscriptionsUsageInterface::class, config('subscriptions.models.subscriptions'));
        $this->app->bind(SubscriptionUsagesInterface::class, config('subscriptions.models.subscription_usages'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        //
    }
}
