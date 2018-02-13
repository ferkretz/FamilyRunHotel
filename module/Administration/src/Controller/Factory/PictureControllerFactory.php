<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\PictureController;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class PictureControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);
        $pictureEntityManager = $container->get(PictureEntityManager::class);
        $defaultUpload = [
            'jpegQuality' => 75,
            'minImageSize' => 256,
            'maxImageSize' => 7680,
            'thumbnailWidth' => 196,
        ];
        $uploadOptions = $siteOptionValueManager->findOneByName('upload', $defaultUpload);

        return new PictureController($pictureEntityManager, $uploadOptions);
    }

}
