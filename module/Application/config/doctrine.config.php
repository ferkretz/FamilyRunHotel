<?php

namespace Application;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity\Locale',
                    __DIR__ . '/../src/Entity\Picture',
                    __DIR__ . '/../src/Entity\Room',
                    __DIR__ . '/../src/Entity\Service',
                    __DIR__ . '/../src/Entity\Site',
                    __DIR__ . '/../src/Entity\User',
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity\Locale' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Picture' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Room' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Service' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\Site' => __NAMESPACE__ . '_driver',
                    __NAMESPACE__ . '\Entity\User' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'customHydrationModes' => [
                    // colum as array
                    'ColumnHydrator' => Hydrator\ColumnHydrator::class
                ],
            ],
        ],
    ],
];
