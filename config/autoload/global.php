<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

return [
    // Language configuration. Available, and listable language..
    'language_config' => [
        'supported' => [
            [
                'locale' => 'hu',
                'name' => 'Hungarian'
            ],
        ],
        // recommend not to change, if you want multilingual page
        'fallback' => [
            'locale' => 'en',
            'name' => 'English'
        ],
    ],
    // Session configuration.
    'session_config' => [
        // one hour coockie time
        'cookie_lifetime' => 60 * 60 * 1,
        // a month
        'gc_maxlifetime' => 60 * 60 * 24 * 30,
    ],
    // Session manager configuration.
    'session_manager' => [
        'validators' => [
            HttpUserAgent::class,
            RemoteAddr::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    // Capability configuration.
    'capability_config' => [
        'admin' => ['admin'],
        'user' => []
    ],
    // File upload configuration.
    'upload_config' => [
        'image' => [
            'maximum_size' => '1048576',
            'supported_types' => [
                IMAGETYPE_GIF => 'gif',
                IMAGETYPE_JPEG => 'jpeg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_BMP => 'bmp'
            ],
        ],
    ],
    // Database configuration.
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => Doctrine\DBAL\Driver\Mysqli\Driver::class,
                'params' => [
                    'dbname' => 'FamilyRunHotel',
                ]
            ],
        ],
    ]
];
