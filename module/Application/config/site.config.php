<?php

namespace Application;

return [
    'site' => [
        /*
         * Site configuration
         */
        // company information
        'company' => [
            'name' => 'Family-run Hotel', // short title
            'i18n' => FALSE,
            'fullName' => 'Family-run Hotel Inc.',
            'email' => NULL,
            'address' => NULL,
            'phone' => NULL,
            'currency' => 'USD',
        ],
        'upload' => [
            'jpegQuality' => 75,
            'minImageSize' => 256,
            'maxImageSize' => 7680,
            'thumbnailWidth' => 196,
        ],
        // google maps
        'google' => [
            'enable' => FALSE,
            'latitude' => 0,
            'longitude' => 0,
            'zoom' => 15,
        ],
        // look (users have own look)
        'look' => [
            'theme' => 'cofee', // coffe is a default theme
            'renderHeader' => 'home', // nowhere|anywhere|home
            'barStyle' => 'default', // default|inverse
        ],
        /*
         * Role configuration
         */
        'roles' => [
            'admin' => [
                'summary' => 'Administrator',
                'capabilities' => ['admin'],
            ],
            'user' => [
                'summary' => 'General user',
                'capabilities' => [],
            ],
        ],
        /*
         * Access (allow) filter configuration (order by preference)
         *
         *    *       : any visitor
         *    !guests : only guests
         *    !users  : only registered user
         *    +filter : group filter (you can use more than one filter)
         *    @filter : email filter (you can use more than one filter)
         *    !own    : check user id (usefull for own properties)
         */
        'accessFilters' => [
            // filter configuration for menu
            'menuItems' => [
                [
                    'ids' => ['mainMenu'],
                    'allow' => ['*'],
                ],
                [
                    'ids' => ['administration'],
                    'allow' => ['+admin'],
                ],
                [
                    'ids' => ['user'],
                    'allow' => ['!users'],
                ],
                [
                    'ids' => ['authenticationLogin', 'authenticationRegister'],
                    'allow' => ['!guests'],
                ],
            ],
        ],
        /*
         * Navigation menu configuration
         */
        'navigationMenu' => [
            [
                'id' => 'mainMenu',
                'icon' => 'menu-hamburger',
                'label' => 'Main menu',
                'i18n' => TRUE,
                'children' => [
                    [
                        'id' => 'homeIndex',
                        'icon' => 'home',
                        'label' => 'Home',
                        'i18n' => TRUE,
                        'route' => 'homeIndex',
                    ],
                    [
                        'id' => 'homeRoom',
                        'icon' => 'bed',
                        'label' => 'Rooms',
                        'i18n' => TRUE,
                        'route' => 'homeRoom',
                    ],
                    [
                        'id' => 'homeVisitorsBook',
                        'icon' => 'book',
                        'label' => 'Visitors\' book',
                        'i18n' => TRUE,
                        'route' => 'homeVisitorsBook',
                    ],
                ],
            ],
            [
                'id' => 'administration',
                'icon' => 'cog',
                'label' => 'Administration',
                'i18n' => TRUE,
                'children' => [
                    [
                        'id' => 'administrationDashboard',
                        'icon' => 'dashboard',
                        'label' => 'Dashboard',
                        'i18n' => TRUE,
                        'route' => 'administrationDashboard',
                    ],
                    [
                        'separator' => TRUE,
                    ],
                    [
                        'id' => 'administrationPicture',
                        'icon' => 'picture',
                        'label' => 'Pictures',
                        'i18n' => TRUE,
                        'route' => 'administrationPicture',
                    ],
                    [
                        'id' => 'administrationService',
                        'icon' => 'cutlery',
                        'label' => 'Services',
                        'i18n' => TRUE,
                        'route' => 'administrationService',
                    ],
                    [
                        'id' => 'administrationRoom',
                        'icon' => 'bed',
                        'label' => 'Rooms',
                        'i18n' => TRUE,
                        'route' => 'administrationRoom',
                    ],
                    [
                        'separator' => TRUE,
                    ],
                    [
                        'id' => 'administrationLocale',
                        'icon' => 'globe',
                        'label' => 'Locales',
                        'i18n' => TRUE,
                        'route' => 'administrationLocale',
                    ],
                    [
                        'id' => 'administrationUser',
                        'icon' => 'user',
                        'label' => 'Users',
                        'i18n' => TRUE,
                        'route' => 'administrationUser',
                    ],
                ],
            ],
            [
                'id' => 'user',
                'icon' => 'user',
                'label' => '%username%',
                'align' => 'right',
                'children' => [
                    [
                        'id' => 'profileSettings',
                        'icon' => 'wrench',
                        'label' => 'Settings',
                        'i18n' => TRUE,
                        'route' => 'profileSettings',
                    ],
                    [
                        'id' => 'profileReservations',
                        'icon' => 'pushpin',
                        'label' => 'My reservations',
                        'i18n' => TRUE,
                    //'route' => 'profileReservations',
                    ],
                    [
                        'separator' => TRUE,
                    ],
                    [
                        'id' => 'authenticationLogout',
                        'icon' => 'log-out',
                        'label' => 'Sign out',
                        'i18n' => TRUE,
                        'route' => 'authenticationLogout',
                    ],
                ],
            ],
            [
                'id' => 'authenticationLogin',
                'icon' => 'log-in',
                'label' => 'Sign in',
                'float' => 'right',
                'i18n' => TRUE,
                'route' => 'authenticationLogin',
            ],
            [
                'id' => 'authenticationRegister',
                'icon' => 'edit',
                'label' => 'Sign up',
                'float' => 'right',
                'i18n' => TRUE,
                'route' => 'authenticationRegister',
            ],
        ],
    ],
];

