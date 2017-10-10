<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\PictureController;
use Administration\Service\PictureQueryManager;
use Administration\Service\PictureManager;
use Application\Service\Localizator;

class PictureControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $pictureQueryManager = $container->get(PictureQueryManager::class);
        $pictureManager = $container->get(PictureManager::class);
        $localizator = $container->get(Localizator::class);

        return new PictureController($pictureQueryManager, $pictureManager, $localizator);
    }

}
