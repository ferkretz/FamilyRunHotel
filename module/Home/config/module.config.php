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
    'site' => [
        'accessFilters' => [
            'controllers' => [
                Controller\IndexController::class => [
                    [
                        'actions' => ['index'],
                        'allow' => ['*'],
                    ],
                ],
                Controller\RoomController::class => [
                    [
                        'actions' => ['index', 'view', 'getPicture'],
                        'allow' => ['*'],
                    ],
                ],
            ],
        ],
    ],
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
                'type' => Segment::class,
                'options' => [
                    'route' => '/rooms[/:action][/:id]',
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
            Controller\RoomController::class => Controller\Factory\RoomControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
