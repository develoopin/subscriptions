<?php


return [


    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you want to use your own models you will want to update the following
    | array to make sure Laraplans use them.
    |
    */

    'models' => [
        'company' => 'Develoopin\Subscription\Models\Core\Company',
        'users' => 'Develoopin\Subscription\Models\Core\Users',
        'features' => 'Develoopin\Subscription\Models\Core\Features',
        'modules' => 'Develoopin\Subscription\Models\Core\Modules',
        'plans' => 'Develoopin\Subscription\Models\Core\Plans',
        'subscriptions' => 'Develoopin\Subscription\Models\Core\Subsriptions',
        'subscription_usages' => 'Develoopin\Subscription\Models\Core\SubsriptionsUsages',

    ],


];
