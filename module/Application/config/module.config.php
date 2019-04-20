<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array_merge(
        include __DIR__ . '/controller.config.php',
        include __DIR__ . '/doctrine.config.php',
        include __DIR__ . '/navigation.config.php',
        include __DIR__ . '/router.config.php',
        include __DIR__ . '/role.config.php',
        include __DIR__ . '/service.config.php',
        include __DIR__ . '/view.config.php'
);
