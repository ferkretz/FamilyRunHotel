<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\Url;
use Application\Service\CapabilityManager;
use Application\Service\UserManager;

class NavManager {

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Url view helper.
     * @var Url
     */
    private $urlHelper;

    /**
     * Entity manager.
     * @var UserManager
     */
    private $userManager;

    /**
     * Capability manager.
     * @var CapabilityManager
     */
    private $capabilityManager;

    public function __construct(AuthenticationService $authenticationService,
                                Url $urlHelper,
                                UserManager $userManager,
                                CapabilityManager $capabilityManager) {
        $this->authenticationService = $authenticationService;
        $this->urlHelper = $urlHelper;
        $this->userManager = $userManager;
        $this->capabilityManager = $capabilityManager;
    }

    public function getMenuItems() {
        $url = $this->urlHelper;
        $items = [];

        $mainMenuItems[] = [
            'id' => 'pictures',
            'glyphicon' => 'picture',
            'label' => 'Pictures',
                //    'link' => $url('pictures')
        ];
        $mainMenuItems[] = [
            'id' => 'rooms',
            'glyphicon' => 'bed',
            'label' => 'Rooms',
                //'link' => $url('rooms')
        ];
        $mainMenuItems[] = [
            'id' => 'services',
            'glyphicon' => 'cutlery',
            'label' => 'Services',
                //'link' => $url('services')
        ];

        if (!$this->authenticationService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'glyphicon' => 'log-in',
                'label' => 'Sign in',
                'link' => $url('login'),
                'float' => 'right'
            ];
            $items[] = [
                'id' => 'register',
                'glyphicon' => 'edit',
                'label' => 'Sign up',
                //'link' => $url('register'),
                'float' => 'right'
            ];
        } else {
            $mainMenuItems[] = [];
            $mainMenuItems[] = [
                'id' => 'users',
                'glyphicon' => 'user',
                'label' => 'Users',
                    //'link' => $url('users')
            ];

            $myMenuItems[] = [
                'id' => 'reservations',
                'glyphicon' => 'pushpin',
                'label' => 'My reservations',
                'link' => $url('logout')
            ];
            $myMenuItems[] = [];
            $myMenuItems[] = [
                'id' => 'logout',
                'glyphicon' => 'log-out',
                'label' => 'Sign out',
                'link' => $url('logout')
            ];

            $user = $this->userManager->findByEmail($this->authenticationService->getIdentity());      
            $items[] = [
                'id' => 'my-menu',
                'glyphicon' => 'user',
                'label' => $user->getDisplayName(),
                'float' => 'right',
                'dropdown' => $myMenuItems
            ];
        }

        $items[] = [
            'id' => 'main-menu',
            'glyphicon' => 'menu-hamburger',
            'label' => 'Main menu',
            'dropdown' => $mainMenuItems
        ];

        return $items;
    }

}
