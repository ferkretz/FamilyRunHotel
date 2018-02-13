<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\RoomController;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Service\ServiceEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class RoomControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $roomEntityManager = $container->get(RoomEntityManager::class);
        $localeEntityManager = $container->get(LocaleEntityManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);
        $serviceEntityManager = $container->get(ServiceEntityManager::class);
        $pictureEntityManager = $container->get(PictureEntityManager::class);
        $defaultUpload = [
            'jpegQuality' => 75,
            'minImageSize' => 256,
            'maxImageSize' => 7680,
            'thumbnailWidth' => 196,
        ];
        $uploadOptions = $siteOptionValueManager->findOneByName('upload', $defaultUpload);

        return new RoomController($roomEntityManager, $localeEntityManager, $siteOptionValueManager, $serviceEntityManager, $pictureEntityManager, $uploadOptions);
    }

}
