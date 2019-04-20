<?php

namespace Application\Service\Option\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Option\LocalizeManager;
use Application\Service\Option\SettingsManager;

class LocalizeManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $settingsManager = $container->get(SettingsManager::class);
        $translator = $container->get(Translator::class);

        return new LocalizeManager($settingsManager, $translator);
    }

}
