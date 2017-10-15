<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'site_options' => [
        'brandname' => 'Family-run Hotel',
        'navBarStyle' => 'default',
        'headerShow' => 'home',
        'theme' => 'coffee',
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
    'language_config' => [
        'languages_dir' => __DIR__ . '/../language/',
    ],
    'service_manager' => [
        'factories' => [
            Model\HeaderData::class => Service\Factory\HeaderDataFactory::class,
            Model\NavBarData::class => Service\Factory\NavBarDataFactory::class,
            Service\CapabilityManager::class => Service\Factory\CapabilityManagerFactory::class,
            Service\DashboardManager::class => Service\Factory\DashboardManagerFactory::class,
            Service\Localizator::class => Service\Factory\LocalizatorFactory::class,
            Service\PictureManager::class => Service\Factory\PictureManagerFactory::class,
            Service\RoomManager::class => Service\Factory\RoomManagerFactory::class,
            Service\RoomServiceManager::class => Service\Factory\RoomServiceManagerFactory::class,
            Service\SiteOptionManager::class => Service\Factory\SiteOptionManagerFactory::class,
            Service\ThemeSelector::class => Service\Factory\ThemeSelectorFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
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
        //View\Helper\NavBar2::class => View\Helper\Factory\NavBarFactory::class,
        //View\Helper\Header::class => View\Helper\Factory\HeaderFactory::class,
        ],
        'aliases' => [
        //'header' => View\Helper\Header::class,
        //'navBar' => View\Helper\NavBar::class,
        ],
        'invokables' => [
            'header' => View\Helper\Header::class,
            'navBar' => View\Helper\NavBar::class,
            'languages' => \Zend\Http\Request::class,
            'translate' => \Zend\I18n\View\Helper\Translate::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => TRUE,
        'display_exceptions' => TRUE,
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
