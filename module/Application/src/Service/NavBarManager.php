<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\I18n\Translator;
use Zend\View\Helper\Url;
use Administration\Service\CapabilityManager;
use Administration\Service\OptionManager;
use Administration\Service\UserManager;

class NavBarManager {

    /**
     * Entity manager.
     * @var OtionManager
     */
    protected $optionManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * Entity manager.
     * @var UserManager
     */
    protected $userManager;

    /**
     * Capability manager.
     * @var CapabilityManager
     */
    protected $capabilityManager;

    /**
     * Url view helper.
     * @var Url
     */
    protected $urlHelper;

    /**
     * Translator.
     * @var Translator
     */
    protected $translator;

    /**
     * Elements of the navbar.
     * @var string
     */
    protected $navbarElements = [];

    public function __construct(OptionManager $optionManager,
                                AuthenticationService $authenticationService,
                                UserManager $userManager,
                                CapabilityManager $capabilityManager,
                                Url $urlHelper,
                                Translator $translator) {
        $this->optionManager = $optionManager;
        $this->authenticationService = $authenticationService;
        $this->userManager = $userManager;
        $this->capabilityManager = $capabilityManager;
        $this->urlHelper = $urlHelper;
        $this->translator = $translator;

        $this->setOptions();
    }

    public function setOptions($options = NULL) {
        $url = $this->urlHelper;

        if (!isset($options['brand_name'])) {
            $options['brand_name'] = $this->optionManager->findByName('brandName', 'FamilyRunHotel');
        }
        if (!isset($options['brand_link'])) {
            $options['brand_link'] = $url('home');
        }
        if (!isset($options['navbar_style'])) {
            $options['navbar_style'] = 'default';
        }

        $this->navbarElements['options'] = $options;
    }

    public function addMenuItem($item) {
        if (!isset($item['id'])) {
            return;
        }

        if (isset($item['parent_id'])) {
            if (!isset($this->navbarElements['menu_items'][$item['parent_id']])) {
                return;
            }
            $parent = &$this->navbarElements['menu_items'][$item['parent_id']]['dropdown'];
        } else {
            $parent = &$this->navbarElements['menu_items'];
        }

        $newItem['id'] = $item['id'];
        if (isset($item['separator'])) {
            $newItem['separator'] = TRUE;
            $parent[$item['id']] = $newItem;
            return;
        }
        if (isset($item['glyphicon'])) {
            $newItem['glyphicon'] = $item['glyphicon'];
        }
        if (isset($item['float'])) {
            $newItem['float'] = $item['float'];
        }
        if (isset($item['label'])) {
            if (isset($item['no_i18n'])) {
                $newItem['label'] = $item['no_i18n'] == TRUE ? $item['label'] : $this->translate($item['label']);
            } else {
                $newItem['label'] = $this->translate($item['label']);
            }
        }
        if (isset($item['link'])) {
            $newItem['link'] = $item['link'];
        }

        $parent[$item['id']] = $newItem;
    }

    public function getDefaultNavBarElements() {
        $url = $this->urlHelper;

        $this->addMenuItem([
            'id' => 'main_menu',
            'glyphicon' => 'menu-hamburger',
            'label' => 'Main menu',
        ]);
        $this->addMenuItem([
            'parent_id' => 'main_menu',
            'id' => 'home',
            'glyphicon' => 'home',
            'label' => 'Home',
            'link' => $url('home'),
        ]);
        $this->addMenuItem([
            'parent_id' => 'main_menu',
            'id' => 'rooms',
            'glyphicon' => 'bed',
            'label' => 'Rooms',
            'link' => $url('home-rooms'),
        ]);
        $this->addMenuItem([
            'parent_id' => 'main_menu',
            'id' => 'visitors_book',
            'glyphicon' => 'book',
            'label' => 'Visitors\' book',
            'link' => $url('home-visitors-book'),
        ]);

        if (!$this->authenticationService->hasIdentity()) {
            $this->addMenuItem([
                'id' => 'login',
                'glyphicon' => 'log-in',
                'label' => 'Sign in',
                'float' => 'right',
                'link' => $url('auth-login'),
            ]);
            $this->addMenuItem([
                'id' => 'register',
                'glyphicon' => 'edit',
                'label' => 'Sign up',
                'float' => 'right',
                'link' => $url('auth-register'),
            ]);
        } else {
            $user = $this->userManager->findByEmail($this->authenticationService->getIdentity());

            if ($user->getRole() == 'admin') {
                $this->addMenuItem([
                    'id' => 'administration',
                    'glyphicon' => 'cog',
                    'label' => 'Administration',
                ]);
                $this->addMenuItem([
                    'parent_id' => 'administration',
                    'id' => 'dashboard',
                    'glyphicon' => 'dashboard',
                    'label' => 'Dashboard',
                    'link' => $url('admin-dashboard'),
                ]);
                $this->addMenuItem([
                    'parent_id' => 'administration',
                    'id' => 'pictures',
                    'glyphicon' => 'picture',
                    'label' => 'Pictures',
                    'link' => $url('admin-pictures'),
                ]);
                $this->addMenuItem([
                    'parent_id' => 'administration',
                    'id' => 'services',
                    'glyphicon' => 'cutlery',
                    'label' => 'Services',
                    'link' => $url('admin-services'),
                ]);
                $this->addMenuItem([
                    'parent_id' => 'administration',
                    'id' => 'rooms',
                    'glyphicon' => 'bed',
                    'label' => 'Rooms',
                    'link' => $url('admin-rooms'),
                ]);
                $this->addMenuItem([
                    'parent_id' => 'administration',
                    'id' => 'users',
                    'glyphicon' => 'user',
                    'label' => 'Users',
                    'link' => $url('admin-users'),
                ]);
            }

            $this->addMenuItem([
                'id' => 'personal',
                'glyphicon' => 'user',
                'label' => $user->getDisplayName(),
                'float' => 'right',
                'no_i18n' => TRUE,
            ]);
            $this->addMenuItem([
                'parent_id' => 'personal',
                'id' => 'settings',
                'glyphicon' => 'wrench',
                'label' => 'Settings',
                    //'link' => $url('personal-settings'),
            ]);
            $this->addMenuItem([
                'parent_id' => 'personal',
                'id' => 'reservations',
                'glyphicon' => 'pushpin',
                'label' => 'My reservations',
                    //'link' => $url('personal-reservations'),
            ]);
            $this->addMenuItem([
                'parent_id' => 'personal',
                'id' => 'logout_separator',
                'separator' => TRUE,
            ]);
            $this->addMenuItem([
                'parent_id' => 'personal',
                'id' => 'logout',
                'glyphicon' => 'log-out',
                'label' => 'Sign out',
                'link' => $url('auth-logout'),
            ]);
        }

        return $this->navbarElements;
    }

    protected function translate($message) {
        return $this->translator->translate($message);
    }

}
