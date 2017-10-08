<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\I18n\Translator;
use Administration\Service\CapabilityManager;
use Administration\Service\OptionManager;
use Administration\Service\UserManager;
use Application\Service\NavBarManager;

class NavBarManagerFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(OptionManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $userManager = $container->get(UserManager::class);
        $capabilityManager = $container->get(CapabilityManager::class);
        $translator = $container->get(Translator::class);

        return new NavBarManager($optionManager, $authenticationService, $userManager, $capabilityManager, $urlHelper, $translator);
    }

}
