<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return [
    'service_manager' => [
        'factories' => [
            Service\Locale\LocaleEntityManager::class => Service\Locale\Factory\LocaleEntityManagerFactory::class,
            Service\Picture\PictureEntityManager::class => Service\Picture\Factory\PictureEntityManagerFactory::class,
            Service\Room\RoomEntityManager::class => Service\Room\Factory\RoomEntityManagerFactory::class,
            Service\Service\ServiceEntityManager::class => Service\Service\Factory\ServiceEntityManagerFactory::class,
            Service\Site\CurrentOptionValueManager::class => Service\Site\Factory\CurrentOptionValueManagerFactory::class,
            Service\Site\SiteOptionEntityManager::class => Service\Site\Factory\SiteOptionEntityManagerFactory::class,
            Service\Site\SiteOptionValueManager::class => Service\Site\Factory\SiteOptionValueManagerFactory::class,
            \Zend\Authentication\AuthenticationService::class => Service\User\Factory\AuthenticationServiceFactory::class,
            Service\User\AuthenticationAdapter::class => Service\User\Factory\AuthenticationAdapterFactory::class,
            Service\User\AuthenticationManager::class => Service\User\Factory\AuthenticationManagerFactory::class,
            Service\User\CurrentUserEntityManager::class => Service\User\Factory\CurrentUserEntityManagerFactory::class,
            Service\User\UserEntityManager::class => Service\User\Factory\UserEntityManagerFactory::class,
            Service\User\UserOptionEntityManager::class => Service\User\Factory\UserOptionEntityManagerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\QueryManagerPlugin::class => Controller\Plugin\Factory\QueryManagerPluginFactory::class,
        ],
        'aliases' => [
            'queryManager' => Controller\Plugin\QueryManagerPlugin::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Site\HeaderHelper::class => View\Helper\Site\Factory\HeaderHelperFactory::class,
            View\Helper\Site\NavigationBarHelper::class => View\Helper\Site\Factory\NavigationBarHelperFactory::class,
        ],
        'aliases' => [
            'header' => View\Helper\Site\HeaderHelper::class,
            'navigationBar' => View\Helper\Site\NavigationBarHelper::class,
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
