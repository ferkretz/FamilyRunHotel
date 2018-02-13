<?php

namespace Home\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Site\SiteOptionValueManager;
use Home\Controller\RoomController;

class RoomControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $roomEntityManager = $container->get(RoomEntityManager::class);
        $localeEntityManager = $container->get(LocaleEntityManager::class);
        $pictureEntityManager = $container->get(PictureEntityManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        return new RoomController($roomEntityManager, $localeEntityManager, $siteOptionValueManager, $pictureEntityManager);
    }

}
