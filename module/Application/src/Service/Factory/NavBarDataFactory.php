<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\I18n\Translator;
use Administration\Controller\DashboardController;
use Administration\Controller\PictureController;
use Administration\Controller\RoomController;
use Administration\Controller\RoomServiceController;
use Administration\Controller\UserController;
use Application\Service\SiteOptionManager;
use Application\Model\NavBarData;
use Authentication\Service\AuthenticationManager;
use Authentication\Service\CurrentUserManager;

class NavBarDataFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(SiteOptionManager::class);
        $authenticationManager = $container->get(AuthenticationManager::class);
        $authenticationService = $container->get(AuthenticationService::class);
        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $currentUserManager = $container->get(CurrentUserManager::class);
        $translator = $container->get(Translator::class);

        $navBarData = new NavBarData();

        $navBarData->setBrandName($optionManager->findValueByName('brandName'));
        $navBarData->setBrandLink($urlHelper('homeIndex'));
        $navBarData->setStyle($optionManager->findCurrentValueByName('navBarStyle'));

        $navBarData->addMenuItem([
            'id' => 'home',
            'glyphicon' => 'menu-hamburger',
            'label' => $translator->translate('Main menu'),
            'grant' => TRUE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'home',
            'id' => 'homeIndex',
            'glyphicon' => 'home',
            'label' => $translator->translate('Home'),
            'link' => $urlHelper('homeIndex'),
            'grant' => TRUE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'home',
            'id' => 'homeRoom',
            'glyphicon' => 'bed',
            'label' => $translator->translate('Rooms'),
            'link' => $urlHelper('homeRoom'),
            'grant' => TRUE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'home',
            'id' => 'homeVisitorsBook',
            'glyphicon' => 'book',
            'label' => $translator->translate('Visitors\' book'),
            'link' => $urlHelper('homeVisitorsBook'),
            'grant' => TRUE,
        ]);

        $navBarData->addMenuItem([
            'id' => 'administration',
            'glyphicon' => 'cog',
            'label' => $translator->translate('Administration'),
            'grant' => $authenticationManager->filterAccess(DashboardController::class, 'company') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'administration',
            'id' => 'administrationDashboard',
            'glyphicon' => 'dashboard',
            'label' => $translator->translate('Dashboard'),
            'link' => $urlHelper('administrationDashboard'),
            'grant' => $authenticationManager->filterAccess(DashboardController::class, 'company') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'administration',
            'id' => 'administrationPicture',
            'glyphicon' => 'picture',
            'label' => $translator->translate('Pictures'),
            'link' => $urlHelper('administrationPicture'),
            'grant' => $authenticationManager->filterAccess(PictureController::class, 'index') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'administration',
            'id' => 'administrationService',
            'glyphicon' => 'cutlery',
            'label' => $translator->translate('Services'),
            'link' => $urlHelper('administrationService'),
            'grant' => $authenticationManager->filterAccess(RoomServiceController::class, 'index') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'administration',
            'id' => 'administrationRoom',
            'glyphicon' => 'bed',
            'label' => $translator->translate('Rooms'),
            'link' => $urlHelper('administrationRoom'),
            'grant' => $authenticationManager->filterAccess(RoomController::class, 'index') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);
        $navBarData->addMenuItem([
            'parent_id' => 'administration',
            'id' => 'administrationUser',
            'glyphicon' => 'user',
            'label' => 'Users',
            'link' => $urlHelper('administrationUser'),
            'grant' => $authenticationManager->filterAccess(UserController::class, 'index') == AuthenticationManager::ACCESS_GRANTED ? TRUE : FALSE,
        ]);

        if ($authenticationService->hasIdentity()) {
            $navBarData->addMenuItem([
                'id' => 'profile',
                'glyphicon' => 'user',
                'label' => $currentUserManager->get()->getDisplayName() ?? $currentUserManager->get()->getRealName(),
                'float' => 'right',
                'grant' => TRUE,
            ]);
            $navBarData->addMenuItem([
                'parent_id' => 'profile',
                'id' => 'profileSettings',
                'glyphicon' => 'wrench',
                'label' => $translator->translate('Settings'),
                'link' => $urlHelper('profileSettings'),
                'grant' => TRUE,
            ]);
            $navBarData->addMenuItem([
                'parent_id' => 'profile',
                'id' => 'profileReservations',
                'glyphicon' => 'pushpin',
                'label' => $translator->translate('My reservations'),
                //'link' => $urlHelper('profileReservations'),
                'grant' => TRUE,
            ]);
            $navBarData->addMenuItem([
                'parent_id' => 'profile',
                'id' => 'profileLogoutSeparator',
                'separator' => TRUE,
                'grant' => TRUE,
            ]);
            $navBarData->addMenuItem([
                'parent_id' => 'profile',
                'id' => 'authenticationLogout',
                'glyphicon' => 'log-out',
                'label' => $translator->translate('Sign out'),
                'link' => $urlHelper('authenticationLogout'),
                'grant' => TRUE,
            ]);
        } else {
            $navBarData->addMenuItem([
                'id' => 'authenticationLogin',
                'glyphicon' => 'log-in',
                'label' => $translator->translate('Sign in'),
                'float' => 'right',
                'link' => $urlHelper('authenticationLogin'),
                'grant' => TRUE,
            ]);
            $navBarData->addMenuItem([
                'id' => 'authenticationRegister',
                'glyphicon' => 'edit',
                'label' => $translator->translate('Sign up'),
                'float' => 'right',
                'link' => $urlHelper('authenticationRegister'),
                'grant' => TRUE,
            ]);
        }

        return $navBarData;
    }

}
