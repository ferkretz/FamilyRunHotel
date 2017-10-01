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
                    'route' => '/admin/pictures[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
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
                    'route' => '/admin/services[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
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
                    'route' => '/admin/rooms[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
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
            Service\DashboardManager::class => Service\Factory\DashboardManagerFactory::class,
            Service\PictureManager::class => Service\Factory\PictureManagerFactory::class,
            Service\RoomServiceManager::class => Service\Factory\RoomServiceManagerFactory::class,
            Service\RoomManager::class => Service\Factory\RoomManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\OptionManager::class => Service\Factory\OptionManagerFactory::class,
            Service\CapabilityManager::class => Service\Factory\CapabilityManagerFactory::class,
            Service\UserQueryManager::class => Service\Factory\UserQueryManagerFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => Controller\Factory\DashboardControllerFactory::class,
            Controller\PictureController::class => Controller\Factory\PictureControllerFactory::class,
            Controller\RoomServiceController::class => Controller\Factory\RoomServiceControllerFactory::class,
            Controller\RoomController::class => Controller\Factory\RoomControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\DashboardController::class => [
                ['actions' => ['index'], 'allow' => '+admin']
            ],
            Controller\PictureController::class => [
                ['actions' => ['index'], 'allow' => '+admin']
            ],
            Controller\RoomServiceController::class => [
                ['actions' => ['index'], 'allow' => '+admin']
            ],
            Controller\RoomController::class => [
                ['actions' => ['index'], 'allow' => '+admin']
            ],
            Controller\UserController::class => [
                ['actions' => ['index'], 'allow' => '+admin']
            ],
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
