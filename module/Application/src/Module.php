<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Application\Model\HeaderData;
use Application\Model\NavBarData;
use Application\Service\Localizator;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;
use Authentication\Controller\AuthenticationController;
use Authentication\Service\AuthenticationManager;

class Module {

    const VERSION = '3.0.3-dev';

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();

        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);

        // Localize
        $localizator = $serviceManager->get(Localizator::class);

        // Load brandname from database ('FamilyRunHotel' is the default).
        $viewModel = $event->getViewModel();
        $optionManager = $serviceManager->get(SiteOptionManager::class);
        $viewModel->brandName = $optionManager->findValueByName('brandName');
        // Select theme
        $themeSelector = $serviceManager->get(ThemeSelector::class);
        $viewModel->theme = $themeSelector->getLocalTheme();
        // Navbar, header
        $viewModel->headerData = $serviceManager->get(HeaderData::class);
        $viewModel->navBarData = $serviceManager->get(NavBarData::class);

        // Controller setup
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    public function onDispatch(MvcEvent $event) {
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', NULL);
        $actionName = $event->getRouteMatch()->getParam('action', NULL);

        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        $authenticationManager = $event->getApplication()->getServiceManager()->get(AuthenticationManager::class);

        if ($controllerName != AuthenticationController::class) {
            $result = $authenticationManager->filterAccess($controllerName, $actionName);

            if ($result == AuthenticationManager::AUTHENTICATION_REQUIRED) {
                $uri = $event->getApplication()->getRequest()->getUri();
                $uri->setScheme(NULL)
                        ->setHost(NULL)
                        ->setPort(NULL)
                        ->setUserInfo(NULL);
                $redirectUrl = $uri->toString();

                return $controller->redirect()->toRoute('authenticationLogin', [], ['query' => ['redirectUrl' => $redirectUrl]]);
            } else if ($result == AuthenticationManager::ACCESS_DENIED) {
                return $controller->redirect()->toRoute('auth-not-authorized');
            }
        }
    }

}
