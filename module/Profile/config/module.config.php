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
                Controller\ReservationController::class => [
                    [
                        'actions' => ['index', 'edit'],
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
                    'route' => '/profile/settings[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action' => 'account',
                    ],
                ],
            ],
            'profileReservation' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile/reservations[/:action][/:id][/:year][/:month]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_]*',
                        'year' => '[0-9_]*',
                        'month' => '[0-9_]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ReservationController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\SettingsController::class => Controller\Factory\SettingsControllerFactory::class,
            Controller\ReservationController::class => Controller\Factory\ReservationControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
