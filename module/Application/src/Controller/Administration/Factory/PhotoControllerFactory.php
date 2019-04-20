<?php

namespace Application\Controller\Administration\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Administration\PhotoController;
use Application\Service\Option\SettingsManager;
use Application\Service\Photo\PhotoManager;
use Application\Service\Photo\PhotoQueryManager;
use Application\Service\User\AccessManager;

class PhotoControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $photoManager = $container->get(PhotoManager::class);
        $photoQueryManager = $container->get(PhotoQueryManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new PhotoController($settingsManager, $photoManager, $photoQueryManager, $accessManager);
    }

}
