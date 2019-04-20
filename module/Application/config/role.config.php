<?php

namespace Application;

/*
 * any - access to anybody's data
 * own - access to only own data (eg.: may, or may write access to posts)
 *
 * settings [manage] - manage settings, and default look & feel, POIs
 * profile [manage] - manage own profile (diplay profile in menu)
 * offers [view|list|add|edit|delete] - manage photos, rooms, services, reservations
 * users [view|list|add|edit|delete] - manage users
 */

return [
    'access_manager' => [
        'roles' => [
            'admin' => [
                'summary' => 'Administrator',
                'capabilities' => [
                    'any' => [
                        'admin' => ['manage'],
                        'settings' => ['manage'],
                        'profile' => ['manage'],
                        'offers' => ['view', 'list', 'add', 'edit', 'delete'],
                        'users' => ['view', 'list', 'add', 'edit', 'delete'],
                    ],
                ],
            ],
            'moderator' => [
                'summary' => 'Moderator',
                'capabilities' => [
                    'any' => [
                        'profile' => ['manage'],
                        'offers' => ['view', 'list'],
                    ],
                ],
            ],
            'user' => [
                'summary' => 'General user',
                'capabilities' => [
                    'any' => [
                        'profile' => ['manage'],
                        'offers' => ['view', 'list'],
                    ],
                ],
            ],
            'guest' => [
                'skip' => true,
                'summary' => 'Guest',
                'capabilities' => [
                    'any' => [
                        'offers' => ['view', 'list'],
                    ],
                ],
            ],
        ],
    ],
];
