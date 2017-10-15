<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\SiteOptionManager;
use Application\Model\HeaderData;

class HeaderDataFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(SiteOptionManager::class);

        $headerData = new HeaderData();

        $headerData->setBrandName($optionManager->findValueByName('brandName'));

        return $headerData;
    }

}
