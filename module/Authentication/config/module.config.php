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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
