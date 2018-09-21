Develoopin Laravel Subscription

Installation

Install via the composer require command:

$ composer require develoopin/subscriptions

Add into config/app.php on providers section/array:

Develoopin\Subscriptions\SubscriptionServiceProvider::class

Publish the config file:

$ php artisan vendor:publish

Migrate database with:

$ php artisan migrate --path=/database/migrations/core
