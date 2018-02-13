<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\User\AuthenticationManager;
use Authentication\Controller\AuthenticationController;

class Module {

    const VERSION = '3.1.0';

    public function getConfig() {
        $config = include __DIR__ . '/../config/module.config.php';

        $handle = opendir(__DIR__ . '/../config');
        if ($handle) {
            while (($entry = readdir($handle)) !== FALSE) {
                if ($entry != '.' && $entry != '..' && $entry != 'module.config.php') {
                    $config = array_merge($config, include __DIR__ . '/../config/' . $entry);
                }
            }
            closedir($handle);
        }

        return $config;
    }

    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);
        $viewModel = $event->getViewModel();

        // Locale
        $this->setLocale($serviceManager);

        // Some variable
        $viewModel->titleString = $this->getTitleString($serviceManager);
        $viewModel->themeString = $this->getThemeString($serviceManager);

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
                return $controller->notFoundAction();
            }
        }
    }

    private function getTitleString($serviceManager) {
        $translator = $serviceManager->get(Translator::class);
        $siteOptionValueManager = $serviceManager->get(SiteOptionValueManager::class);

        $defaultCompanyData = [
            'name' => 'Family-run Hotel',
            'i18n' => FALSE,
        ];
        $companyData = $siteOptionValueManager->findOneByName('company', $defaultCompanyData);

        if ($companyData['i18n']) {
            return $translator->translate($companyData['name']);
        }

        return $companyData['name'];
    }

    private function getThemeString($serviceManager) {
        $currentOptionValueManager = $serviceManager->get(CurrentOptionValueManager::class);

        $defaultLookData = [
            'theme' => 'cofee',
        ];
        $lookData = $currentOptionValueManager->findOneByName('look', $defaultLookData);
        $currentThemeName = $lookData['theme'];

        $themesDir = __DIR__ . '/../../../public/themes';
        $supportedThemeNames = [];

        if (is_dir($themesDir)) {
            $handle = opendir($themesDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != '.' && $entry != '..') {
                        $supportedThemeNames[] = $entry;
                    }
                }
                closedir($handle);
            }
        }

        if (in_array($currentThemeName, $supportedThemeNames)) {
            return $currentThemeName;
        }

        return $supportedThemeNames[0];
    }

    private function setLocale($serviceManager) {
        $translator = $serviceManager->get(Translator::class);
        $currentLocale = $this->findCurrentLocale($serviceManager);
        $languageDir = __DIR__ . '/../language/' . $currentLocale;

        if (is_dir($languageDir)) {
            $handle = opendir($languageDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != '.' && $entry != '..') {
                        if (strrpos($entry, '.php') === (strlen($entry) - 4)) {
                            $translator->addTranslationFile('phpArray', $languageDir . '/' . $entry, 'default', $currentLocale);
                        }
                    }
                }
                closedir($handle);
            }
        }

        AbstractValidator::setDefaultTranslator($translator);
        \Locale::setDefault($currentLocale);
    }

    private function findCurrentLocale($serviceManager) {
        $localeEntityManager = $serviceManager->get(LocaleEntityManager::class);
        $supportedLocales = $localeEntityManager->findAllName();

        $headers = $serviceManager->get('Request')->getHeaders();
        if ($headers->has('Accept-Language')) {
            $requestedLocales = $headers->get('Accept-Language')->getPrioritized();
        }

        foreach ($requestedLocales as $requestedLocale) {
            $currentLocale = \Locale::lookup($supportedLocales, $requestedLocale->typeString, TRUE, '.');
            if ($currentLocale != '.') {
                $localeEntityManager->setCurrentByName($currentLocale);
                return $currentLocale;
            }
        }

        $currentLocaleEntity = $localeEntityManager->findOneById(1);
        $localeEntityManager->setCurrent($currentLocaleEntity);
        return $currentLocaleEntity->getName();
    }

}
