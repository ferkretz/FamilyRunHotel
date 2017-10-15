<?php

namespace Profile;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'profileSettings' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile/settings/:action',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action' => 'account',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\SettingsController::class => Controller\Factory\SettingsControllerFactory::class,
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\SettingsController::class => [
                ['actions' => ['account', 'look'], 'allow' => '@']
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            //Service\PictureQueryManager::class => Service\Factory\PictureQueryManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
