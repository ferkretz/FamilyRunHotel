<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'home-rooms' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/rooms',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'rooms',
                    ],
                ],
            ],
            'home-visitors-book' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/visitors-book',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'visitorsBook',
                    ],
                ],
            ],
            'auth-register' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'register',
                    ],
                ],
            ],
            'auth-login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'auth-logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'auth-not-authorized' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/not-authorized',
                    'defaults' => [
                        'controller' => Controller\AuthenticationController::class,
                        'action' => 'notAuthorized',
                    ],
                ],
            ],
            'auth-reset-password' => [
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
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => 'zend.%s.php',
            ],
            [
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ],
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthenticationAdapter::class => Service\Factory\AuthenticationAdapterFactory::class,
            Service\AuthenticationManager::class => Service\Factory\AuthenticationManagerFactory::class,
            Service\NavBarManager::class => Service\Factory\NavBarManagerFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthenticationController::class => Controller\Factory\AuthenticationControllerFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\TranslatorPlugin::class => Controller\Plugin\Factory\TranslatorPluginFactory::class,
        ],
        'aliases' => [
            'translator' => Controller\Plugin\TranslatorPlugin::class,
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index', 'rooms', 'visitorsBook'], 'allow' => '*']
            ],
        ]
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\NavBar::class => View\Helper\Factory\NavBarFactory::class,
            View\Helper\Header::class => View\Helper\Factory\HeaderFactory::class,
        ],
        'aliases' => [
            'header' => View\Helper\Header::class,
            'navBar' => View\Helper\NavBar::class,
        ],
        'invokables' => [
            'languages' => \Zend\Http\Request::class,
            'translate' => \Zend\I18n\View\Helper\Translate::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'paginator/paginator' => __DIR__ . '/../view/paginator/paginator.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
