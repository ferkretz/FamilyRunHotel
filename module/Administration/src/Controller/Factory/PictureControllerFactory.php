<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\PictureController;
use Administration\Service\PictureQueryManager;
use Application\Service\Localizator;
use Application\Service\PictureManager;
use Application\Service\SiteOptionManager;

class PictureControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $pictureQueryManager = $container->get(PictureQueryManager::class);
        $pictureManager = $container->get(PictureManager::class);
        $localizator = $container->get(Localizator::class);
        $optionManager = $container->get(SiteOptionManager::class);

        return new PictureController($pictureQueryManager, $pictureManager, $localizator, $optionManager);
    }

}
