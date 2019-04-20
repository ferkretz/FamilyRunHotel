<?php

namespace Application;

return [
    'service_manager' => [
        'factories' => [
            Service\Option\LocalizeManager::class => Service\Option\Factory\LocalizeManagerFactory::class,
            Service\Option\OptionManager::class => Service\Option\Factory\OptionManagerFactory::class,
            Service\Option\SettingsManager::class => Service\Option\Factory\SettingsManagerFactory::class,
            Service\Photo\PhotoManager::class => Service\Photo\Factory\PhotoManagerFactory::class,
            Service\Photo\PhotoQueryManager::class => Service\Photo\Factory\PhotoQueryManagerFactory::class,
            Service\Point\PointManager::class => Service\Point\Factory\PointManagerFactory::class,
            Service\Point\PointQueryManager::class => Service\Point\Factory\PointQueryManagerFactory::class,
            Service\Reservation\ReservationManager::class => Service\Reservation\Factory\ReservationManagerFactory::class,
            Service\Room\RoomManager::class => Service\Room\Factory\RoomManagerFactory::class,
            Service\Room\RoomQueryManager::class => Service\Room\Factory\RoomQueryManagerFactory::class,
            Service\Service\ServiceManager::class => Service\Service\Factory\ServiceManagerFactory::class,
            Service\Service\ServiceQueryManager::class => Service\Service\Factory\ServiceQueryManagerFactory::class,
            Service\User\AccessManager::class => Service\User\Factory\AccessManagerFactory::class,
            Service\User\AuthenticationAdapter::class => Service\User\Factory\AuthenticationAdapterFactory::class,
            Service\User\AuthenticationManager::class => Service\User\Factory\AuthenticationManagerFactory::class,
            \Zend\Authentication\AuthenticationService::class => Service\User\Factory\AuthenticationServiceFactory::class,
            Service\User\UserManager::class => Service\User\Factory\UserManagerFactory::class,
            Service\User\UserQueryManager::class => Service\User\Factory\UserQueryManagerFactory::class,
        ],
    ],
];
