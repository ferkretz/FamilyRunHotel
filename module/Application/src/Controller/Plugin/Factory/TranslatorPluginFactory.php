<?php

namespace Application\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Plugin\TranslatorPlugin;

class TranslatorPluginFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $translator = $container->get(Translator::class);

        return new TranslatorPlugin($translator);
    }

}
