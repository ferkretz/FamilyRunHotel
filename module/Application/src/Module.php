<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Administration\Service\OptionManager;
use Application\Controller\AuthenticationController;
use Application\Service\AuthenticationManager;

class Module {

    const VERSION = '3.0.3-dev';

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    private function findPreferredLocale($headers,
                                         $languageConfig) {
        $fallbackLocale = $languageConfig['fallback']['locale'];

        if ($headers->has('Accept-Language')) {
            $acceptLocales = $headers->get('Accept-Language')->getPrioritized();
            $supportedArrays = $languageConfig['supported'];

            foreach ($acceptLocales as $acceptLocale) {
                foreach ($supportedArrays as $supportedArray) {
                    $supportedLocale = $supportedArray['locale'];
                    if (substr($acceptLocale->typeString, 0, strlen($supportedLocale)) === $supportedLocale) {
                        return $supportedLocale;
                    }
                }
                if (substr($acceptLocale->typeString, 0, strlen($fallbackLocale)) === $fallbackLocale) {
                    return $fallbackLocale;
                }
            }
        }

        return $fallbackLocale;
    }

    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();

        // Get acceptable language from browser, and choose a language from 'language_config'.
        $headers = $application->getRequest()->getHeaders();

        // Set preferred locale.
        $languageConfig = $application->getConfig()['language_config'];
        $locale = $this->findPreferredLocale($headers, $languageConfig);
        \Locale::setDefault($locale);

        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);

        $translator = $serviceManager->get(Translator::class);
        AbstractValidator::setDefaultTranslator($translator);

        // Load brandname from database ('FamilyRunHotel' is the default).
        $viewModel = $event->getViewModel();
        $optionManager = $serviceManager->get(OptionManager::class);
        $viewModel->brandName = $optionManager->findByName('brand_name', 'FamilyRunHotel');

        // Controller setup
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

                return $controller->redirect()->toRoute('auth-login', [], ['query' => ['redirectUrl' => $redirectUrl]]);
            } else if ($result == AuthenticationManager::ACCESS_DENIED) {
                return $controller->redirect()->toRoute('auth-not-authorized');
            }
        }
    }

}
