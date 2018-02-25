<?php

namespace Application\Service\Locale\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Locale\CurrentLocaleEntityManager;

class CurrentLocaleEntityManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $localeEntityManager = $container->get(LocaleEntityManager::class);
        $headers = $container->get('Request')->getHeaders();
        $requestedLocales = [];
        if ($headers->has('Accept-Language')) {
            $requestedLocales = $headers->get('Accept-Language')->getPrioritized();
        }

        return new CurrentLocaleEntityManager($localeEntityManager, $requestedLocales);
    }

}
