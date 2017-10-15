<?php

namespace Administration;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'administrationDashboard' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/dashboard/:action',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'company',
                    ],
                ],
            ],
            'administrationPicture' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/pictures[/:action][/:id][/:translationLocale]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'translationLocale' => '[a-zA-Z][a-zA-Z_-]*',
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
                    'route' => '/admin/services[/:action][/:id][/:translationLocale]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'translationLocale' => '[a-zA-Z][a-zA-Z_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\RoomServiceController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'administrationRoom' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/rooms[/:action][/:id][/:translationLocale]',
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
            Controller\PictureController::class => Controller\Factory\PictureControllerFactory::class,
            Controller\RoomController::class => Controller\Factory\RoomControllerFactory::class,
            Controller\RoomServiceController::class => Controller\Factory\RoomServiceControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\DashboardController::class => [
                ['actions' => ['company', 'google', 'look'], 'allow' => '+admin']
            ],
            Controller\PictureController::class => [
                ['actions' => ['index', 'add', 'edit'], 'allow' => '+admin']
            ],
            Controller\RoomController::class => [
                ['actions' => ['index', 'add', 'edit'], 'allow' => '+admin']
            ],
            Controller\RoomServiceController::class => [
                ['actions' => ['index', 'add', 'edit'], 'allow' => '+admin']
            ],
            Controller\UserController::class => [
                ['actions' => ['index', 'add', 'edit'], 'allow' => '+admin']
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\PictureQueryManager::class => Service\Factory\PictureQueryManagerFactory::class,
            Service\RoomQueryManager::class => Service\Factory\RoomQueryManagerFactory::class,
            Service\RoomServiceQueryManager::class => Service\Factory\RoomServiceQueryManagerFactory::class,
            Service\UserQueryManager::class => Service\Factory\UserQueryManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
