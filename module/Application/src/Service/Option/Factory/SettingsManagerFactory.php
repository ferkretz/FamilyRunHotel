<?php

namespace Application\Service\Option\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Option\SettingsManager;
use Application\Service\Option\OptionManager;
use Application\Service\User\AuthenticationManager;

class SettingsManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $uthenticationManager = $container->get(AuthenticationManager::class);
        $optionManager = $container->get(OptionManager::class);
        $headers = $container->get('Request')->getHeaders();
        $requestedLocales = [];
        if ($headers->has('Accept-Language')) {
            $requestedLocales = $headers->get('Accept-Language')->getPrioritized();
        }

        return new SettingsManager($uthenticationManager, $optionManager, $requestedLocales);
    }

}
