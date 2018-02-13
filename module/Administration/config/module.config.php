<?php

namespace Administration;

use Zend\Router\Http\Segment;

return [
    'site' => [
        'accessFilters' => [
            'controllers' => [
                Controller\DashboardController::class => [
                    [
                        'actions' => ['features', 'look'],
                        'allow' => ['+admin'],
                    ],
                ],
                Controller\PictureController::class => [
                    [
                        'actions' => ['index', 'add', 'thumbnail'],
                        'allow' => ['+admin'],
                    ],
                ],
                Controller\RoomController::class => [
                    [
                        'actions' => ['index', 'add', 'edit', 'availableServices', 'selectedServices', 'availablePictures', 'selectedPictures'],
                        'allow' => ['+admin'],
                    ],
                ],
                Controller\ServiceController::class => [
                    [
                        'actions' => ['index', 'add', 'edit'],
                        'allow' => ['+admin'],
                    ],
                ],
                Controller\LocaleController::class => [
                    [
                        'actions' => ['preferred', 'available'],
                        'allow' => ['+admin'],
                    ],
                ],
                Controller\UserController::class => [
                    [
                        'actions' => ['index', 'add', 'edit'],
                        'allow' => ['+admin'],
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'administrationDashboard' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/dashboard[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'features',
                    ],
                ],
            ],
            'administrationPicture' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/pictures[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\PictureController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'administrationService' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/services[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'translationLocale' => '[a-zA-Z][a-zA-Z_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ServiceController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'administrationRoom' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/rooms[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'translationLocale' => '[a-zA-Z][a-zA-Z_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\RoomController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'administrationLocale' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/locales[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\LocaleController::class,
                        'action' => 'preferred',
                    ],
                ],
            ],
            'administrationUser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/users[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => Controller\Factory\DashboardControllerFactory::class,
            Controller\LocaleController::class => Controller\Factory\LocaleControllerFactory::class,
            Controller\PictureController::class => Controller\Factory\PictureControllerFactory::class,
            Controller\RoomController::class => Controller\Factory\RoomControllerFactory::class,
            Controller\ServiceController::class => Controller\Factory\ServiceControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
