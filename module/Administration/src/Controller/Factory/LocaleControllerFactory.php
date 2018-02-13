<?php

namespace Administration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administration\Controller\LocaleController;
use Application\Service\Locale\LocaleEntityManager;

class LocaleControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $localeEntityManager = $container->get(LocaleEntityManager::class);

        return new LocaleController($localeEntityManager);
    }

}
