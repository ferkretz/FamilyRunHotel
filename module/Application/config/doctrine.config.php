<?php

namespace Application;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'result_cache' => 'array',
            ],
        ],
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '../src/Entity/Option',
                    __DIR__ . '../src/Entity/Photo',
                    __DIR__ . '../src/Entity/Point',
                    __DIR__ . '../src/Entity/Reservation',
                    __DIR__ . '../src/Entity/Room',
                    __DIR__ . '../src/Entity/Service',
                    __DIR__ . '../src/Entity/User',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity\Option' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Photo' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Point' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Reservation' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Room' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Service' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\User' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
