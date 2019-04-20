<?php

namespace Application;

return [
    'controllers' => [
        'factories' => [
            Controller\Administration\LookController::class => Controller\Administration\Factory\LookControllerFactory::class,
            Controller\Administration\PhotoController::class => Controller\Administration\Factory\PhotoControllerFactory::class,
            Controller\Administration\PointController::class => Controller\Administration\Factory\PointControllerFactory::class,
            Controller\Administration\RoomController::class => Controller\Administration\Factory\RoomControllerFactory::class,
            Controller\Administration\ServiceController::class => Controller\Administration\Factory\ServiceControllerFactory::class,
            Controller\Administration\SettingController::class => Controller\Administration\Factory\SettingControllerFactory::class,
            Controller\Administration\UserController::class => Controller\Administration\Factory\UserControllerFactory::class,
            Controller\Index\AuthenticationController::class => Controller\Index\Factory\AuthenticationControllerFactory::class,
            Controller\Index\IndexController::class => Controller\Index\Factory\IndexControllerFactory::class,
            Controller\Index\PhotoController::class => Controller\Index\Factory\PhotoControllerFactory::class,
            Controller\Index\RoomController::class => Controller\Index\Factory\RoomControllerFactory::class,
            Controller\Profile\AccountController::class => Controller\Profile\Factory\AccountControllerFactory::class,
            Controller\Profile\LookController::class => Controller\Profile\Factory\LookControllerFactory::class,
            Controller\Profile\PasswordController::class => Controller\Profile\Factory\PasswordControllerFactory::class,
        ],
    ],
];
