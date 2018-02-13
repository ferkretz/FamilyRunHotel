<?php

namespace Profile;

use Zend\Router\Http\Segment;

return [
    'site' => [
        'accessFilters' => [
            'controllers' => [
                Controller\SettingsController::class => [
                    [
                        'actions' => ['account', 'look'],
                        'allow' => ['!users'],
                    ],
                ],
            ],
        ],
    ],
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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
