<?php

namespace Administration;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'admin-dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin/dashboard',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin-pictures' => [
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
            'admin-services' => [
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
            'admin-rooms' => [
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
            'admin-users' => [
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
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\CapabilityManager::class => Service\Factory\CapabilityManagerFactory::class,
            Service\DashboardManager::class => Service\Factory\DashboardManagerFactory::class,
            Service\OptionManager::class => Service\Factory\OptionManagerFactory::class,
            Service\PictureManager::class => Service\Factory\PictureManagerFactory::class,
            Service\PictureQueryManager::class => Service\Factory\PictureQueryManagerFactory::class,
            Service\RoomManager::class => Service\Factory\RoomManagerFactory::class,
            Service\RoomQueryManager::class => Service\Factory\RoomQueryManagerFactory::class,
            Service\RoomServiceManager::class => Service\Factory\RoomServiceManagerFactory::class,
            Service\RoomServiceQueryManager::class => Service\Factory\RoomServiceQueryManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\UserQueryManager::class => Service\Factory\UserQueryManagerFactory::class,
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
                ['actions' => ['index'], 'allow' => '+admin']
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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
