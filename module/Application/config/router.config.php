<?php

namespace Application;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'index' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\Index\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'index-logout' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/logout[/]',
                    'defaults' => [
                        'controller' => Controller\Index\AuthenticationController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'index-login' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/login[/]',
                    'defaults' => [
                        'controller' => Controller\Index\AuthenticationController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'index-register' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/register[/]',
                    'defaults' => [
                        'controller' => Controller\Index\AuthenticationController::class,
                        'action' => 'register',
                    ],
                ],
            ],
            'index-photo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/photo[/:id][/]',
                    'constraints' => [
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Index\PhotoController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'index-room' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/room[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Index\RoomController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'index-book' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/book[/:action][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Index\BookController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin[/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\SettingController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin-room' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/room[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\RoomController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'admin-service' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/service[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\ServiceController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'admin-photo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/photo[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\PhotoController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'admin-reserv' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/reserv[/:action][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\ReservationController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'admin-setting' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/setting[/:action][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\SettingController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin-look' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/look[/:action][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\LookController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'admin-point' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/point[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\PointController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'admin-user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/user[/:action][/:id][/]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[1-9][0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\Administration\UserController::class,
                        'action' => 'list',
                    ],
                ],
            ],
            'profile' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile[/]',
                    'defaults' => [
                        'controller' => Controller\Profile\AccountController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'profile-account' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile/account[/]',
                    'defaults' => [
                        'controller' => Controller\Profile\AccountController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'profile-look' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile/look[/]',
                    'defaults' => [
                        'controller' => Controller\Profile\LookController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'profile-password' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/profile/password[/]',
                    'defaults' => [
                        'controller' => Controller\Profile\PasswordController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
];
