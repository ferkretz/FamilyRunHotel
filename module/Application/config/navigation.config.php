<?php

namespace Application;

return [
    'navigation' => [
        'menu' => [
            /* Company name */
            'brand' => [
                'icon' => false,
                'text' => '%COMPANYNAME%',
                'skipI18n' => true,
                'route' => ['index', [], []],
            ],
            /* Home */
            'home' => [
                'icon' => 'start', 'text' => 'Main menu',
                'allowAny' => ['%GUEST%', '%USER%'],
                'children' => [
                    'index' => [
                        'icon' => 'home', 'text' => 'Home',
                        'route' => ['index', [], []],
                        'allowAny' => ['%GUEST%', '%USER%']
                    ],
                    'room' => [
                        'icon' => 'room', 'text' => 'Rooms',
                        'route' => ['index-room', [], []],
                        'allowAny' => ['%GUEST%', '%USER%']
                    ],
                    'book' => [
                        'icon' => 'book', 'text' => 'Visitors\' book',
                        'route' => ['index-book', [], []],
                        'allowAny' => ['%GUEST%', '%USER%']
                    ],
                ],
            ],
            /* Authentication */
            'login' => [
                'icon' => 'login', 'text' => 'Sign in',
                'route' => ['index-login', ['action' => 'login'], []],
                'allowAll' => ['%GUEST%'],
            ],
            'register' => [
                'icon' => 'register', 'text' => 'Sign up',
                'route' => ['index-register', ['action' => 'register'], []],
                'allowAll' => ['%GUEST%'],
            ],
            /* Administration */
            'admin' => [
                'icon' => 'admin', 'text' => 'Administration',
                'allowAny' => ['manage.admin'],
                'children' => [
                    'room' => [
                        'icon' => 'room', 'text' => 'Rooms',
                        'route' => ['admin-room', [], []],
                        'allowAll' => ['list.offers'],
                    ],
                    'service' => [
                        'icon' => 'service', 'text' => 'Services',
                        'route' => ['admin-service', [], []],
                        'allowAll' => ['list.offers'],
                    ],
                    'photo' => [
                        'icon' => 'photo', 'text' => 'Photos',
                        'route' => ['admin-photo', [], []],
                        'allowAll' => ['list.offers'],
                    ],
                    'reserv' => [
                        'icon' => 'reservation', 'text' => 'Reservations',
                        'route' => ['admin-reserv', [], []],
                        'allowAll' => ['list.offers'],
                    ],
                    [
                        'separator' => true,
                        'allowAll' => ['list.offers', 'manage.settings'],
                    ],
                    'settings' => [
                        'icon' => 'settings', 'text' => 'Settings',
                        'route' => ['admin-setting', [], []],
                        'allowAll' => ['manage.settings'],
                    ],
                    'look' => [
                        'icon' => 'look', 'text' => 'Look & feel',
                        'route' => ['admin-look', [], []],
                        'allowAll' => ['manage.settings'],
                    ],
                    'point' => [
                        'icon' => 'poi', 'text' => 'POIs',
                        'route' => ['admin-point', [], []],
                        'allowAll' => ['manage.settings'],
                    ],
                    [
                        'separator' => true,
                        'allowAny' => ['manage.settings', 'list.user'],
                    ],
                    'user' => [
                        'icon' => 'user', 'text' => 'Users',
                        'route' => ['admin-user', [], []],
                        'allowAll' => ['list.users'],
                    ],
                ],
            ],
            /* Profile */
            'profile' => [
                'icon' => 'user', 'text' => '%USERNAME%',
                'skipI18n' => true, 'right' => true,
                'allowAny' => ['manage.profile'],
                'children' => [
                    [
                        'icon' => 'user', 'text' => 'Account settings',
                        'route' => ['profile-account', [], []],
                        'allowAll' => ['manage.profile']
                    ],
                    [
                        'icon' => 'password', 'text' => 'Password',
                        'route' => ['profile-password', [], []],
                        'allowAll' => ['manage.profile']
                    ],
                    [
                        'icon' => 'look', 'text' => 'Look & feel',
                        'route' => ['profile-look', [], []],
                        'allowAll' => ['manage.profile']
                    ],
                    [
                        'separator' => true,
                        'allowAll' => ['manage.profile']
                    ],
                    [
                        'icon' => 'logout', 'text' => 'Sign out',
                        'route' => ['index-logout', [], []],
                        'allowAll' => ['manage.profile']
                    ],
                ],
            ],
        ],
    ],
];
