<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Home;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'homeIndex' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'homeRoom' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/rooms',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'rooms',
                    ],
                ],
            ],
            'homeVisitorsBook' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/visitors-book',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'visitorsBook',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index', 'rooms', 'visitorsBook'], 'allow' => '*']
            ],
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
