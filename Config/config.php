<?php

return [
    'name'        => 'MauticLimiterBundle',
    'description' => '',
    'version'     => '1.0',
    'author'      => 'MTCExtendee',

    'routes' => [
        'api'    => [
            'mautic_limiter_api_get' => [
                'path'       => '/limiter/get',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:get',
                'method'     => 'GET',
            ],
            'mautic_limiter_api_get_message' => [
                'path'       => '/limiter/message/get',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:getMessage',
                'method'     => 'GET',
            ],
            'mautic_limiter_api_get_limit' => [
                'path'       => '/limiter/limit/get',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:getLimit',
                'method'     => 'GET',
            ],
            'mautic_limiter_api_get_routes' => [
                'path'       => '/limiter/routes/get',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:getRoutes',
                'method'     => 'GET',
            ],
            'mautic_limiter_api_update_message' => [
                'path'       => '/limiter/message/update',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:updateMessage',
                'method'     => 'POST',
            ],

            'mautic_limiter_api_update_limit' => [
                'path'       => '/limiter/limit/update',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:updateLimit',
                'method'     => 'POST',
            ],

            'mautic_limiter_api_update_routes' => [
                'path'       => '/limiter/routes/update',
                'controller' => 'MauticLimiterBundle:Api\LimiterApi:updateRoutes',
                'method'     => 'POST',
            ],
        ],
    ],

    'services'   => [
        'events'       => [
            'mautic.limiter.subscriber.asset'      => [
                'class'     => \MauticPlugin\MauticLimiterBundle\EventListener\AssetSubscriber::class,
                'arguments' => [
                    'mautic.limiter.limiter'
                ],
            ],
        ],
        'forms'        => [
        ],
        'models'       => [

        ],
        'others'       => [
            'mautic.limiter.limiter' => [
                'class'     => \MauticPlugin\MauticLimiterBundle\Service\Limiter::class,
                'arguments' => [
                    'mautic.limiter.settings',
                    'mautic.limiter.service.js'
                ],
            ],
            'mautic.limiter.service.js' => [
                'class'     => \MauticPlugin\MauticLimiterBundle\Service\LimiterJs::class,
                'arguments' => [
                    'router',
                    'mautic.limiter.settings'
                ],
            ],
            'mautic.limiter.settings' => [
                'class'     => \MauticPlugin\MauticLimiterBundle\Integration\LimiterSettings::class,
                'arguments' => [
                    'mautic.helper.core_parameters',
                    'doctrine.dbal.default_connection'
                ],
            ],
        ],
        'controllers'  => [
        ],
        'commands'     => [

        ],
    ],
    'parameters' => [
    ],
];