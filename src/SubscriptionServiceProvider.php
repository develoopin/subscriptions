<?php

namespace Develoopin\Subscriptions;

use Illuminate\Support\ServiceProvider;
use Develoopin\Subscriptions;

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
            __DIR__.'/database/migrations/core/' => database_path('migrations/core/')
        ], 'migrations-subscriptions');

        $this->publishes([
            __DIR__.'/models/core/' => app_path('models/core/')
        ], 'models-subscriptions');

        $this->publishes([
            __DIR__.'/config/subscriptions.php' => config_path('subscriptions.php')
        ], 'config');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/subscriptions.php', 'subscriptions');

        $this->app->bind(FeaturesInterface::class, config('subscriptions.models.core.features'));
        $this->app->bind(ModulsInterface::class, config('subscriptions.models.core.moduls'));
        $this->app->bind(PlansInterface::class, config('subscriptions.models.core.plans'));
        $this->app->bind(SubscriptionsUsageInterface::class, config('subscriptions.models.core.subscriptions'));
        $this->app->bind(SubscriptionUsagesInterface::class, config('subscriptions.models.core.subscription_usages'));
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
