<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Application\Service\CapabilityManager;
use Application\Service\NavManager;
use Application\Service\UserManager;

class NavManagerFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = null) {
        $authenticationService = $container->get(AuthenticationService::class);

        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $userManager = $container->get(UserManager::class);
        $capabilityManager = $container->get(CapabilityManager::class);

        return new NavManager($authenticationService, $urlHelper, $userManager, $capabilityManager);
    }

}
