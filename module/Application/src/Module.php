<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Session\SessionManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\AuthenticationController;
use Application\Service\AuthenticationManager;
use Application\Service\OptionManager;

class Module {

    const VERSION = '3.0.3-dev';

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);

        $viewModel = $event->getViewModel();
        $optionManager = $serviceManager->get(OptionManager::class);
        $viewModel->brandName = $optionManager->findByName('brand_name', 'FamilyRunHotel');

        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    public function onDispatch(MvcEvent $event) {
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        $authentiationManager = $event->getApplication()->getServiceManager()->get(AuthenticationManager::class);

        if ($controllerName != AuthenticationController::class) {
            $result = $authentiationManager->filterAccess($controllerName, $actionName);

            if ($result == AuthenticationManager::AUTHENTICATION_REQUIRED) {
                $uri = $event->getApplication()->getRequest()->getUri();
                $uri->setScheme(null)
                        ->setHost(null)
                        ->setPort(null)
                        ->setUserInfo(null);
                $redirectUrl = $uri->toString();

                return $controller->redirect()->toRoute('login', [], ['query' => ['redirectUrl' => $redirectUrl]]);
            } else if ($result == AuthenticationManager::ACCESS_DENIED) {
                return $controller->redirect()->toRoute('not-authorized');
            }
        }
    }

}
