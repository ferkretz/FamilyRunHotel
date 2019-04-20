<?php

namespace Application\Controller\Index\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Index\PhotoController;
use Application\Service\Photo\PhotoManager;
use Application\Service\User\AccessManager;

class PhotoControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $photoManager = $container->get(PhotoManager::class);
        $accessManager = $container->get(AccessManager::class);

        return new PhotoController($photoManager, $accessManager);
    }

}
