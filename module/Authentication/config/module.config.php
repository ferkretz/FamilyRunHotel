<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authentication;

use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'authenticationRegister' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'register',
                    ],
                ],
            ],
            'authenticationLogin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'authenticationLogout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'authenticationNotAuthorized' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/not-authorized',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'notAuthorized',
                    ],
                ],
            ],
            'authenticationResetPassword' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'resetPassword',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthenticationController::class => Controller\Factory\AuthenticationControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthenticationAdapter::class => Service\Factory\AuthenticationAdapterFactory::class,
            Service\AuthenticationManager::class => Service\Factory\AuthenticationManagerFactory::class,
            Service\CurrentUserManager::class => Service\Factory\CurrentUserManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
