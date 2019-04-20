<?php

namespace Application\View\Helper;

use Zend\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;

class Navbar extends AbstractHelper {

    protected $accessManager;
    protected $authenticationManager;
    protected $routeMatch;
    protected $settingsManager;
    protected $menu;

    public function __construct(AccessManager $accessManager,
                                AuthenticationManager $authenticationManager,
                                ?RouteMatch $routeMatch,
                                SettingsManager $settingsManager,
                                ?array $menu = null) {
        $this->accessManager = $accessManager;
        $this->authenticationManager = $authenticationManager;
        $this->routeMatch = $routeMatch;
        $this->settingsManager = $settingsManager;
        $this->menu = [];

        if ($menu != null) {
            $this->addMenu($menu);
        }
    }

    public function addMenu(array $menu) {
        foreach ($menu as $itemId => $item) {
            $item['itemId'] = $itemId;
            $this->addMenuItem($item);
            if (!empty($item['children'])) {
                foreach ($item['children'] as $subItem) {
                    $subItem['parentId'] = $itemId;
                    $this->addMenuItem($subItem);
                }
            }
        }
    }

    public function addMenuItem(array $item) {
        $container = null;

        if (empty($item['parentId'])) {
            if (!empty($item['itemId'])) {
                $this->menu[$item['itemId']]['right'] = empty($item['right']) ? false : $item['right'];
                $this->menu[$item['itemId']]['children'] = [];
                $container = &$this->menu[$item['itemId']];
            }
        } else {
            $index = count($this->menu[$item['parentId']]['children']);
            $this->menu[$item['parentId']]['children'][$index] = [];
            $container = &$this->menu[$item['parentId']]['children'][$index];
        }

        if ($container !== null) {
            $container['icon'] = empty($item['icon']) ? false : $item['icon'];
            $container['text'] = empty($item['text']) ? false : $item['text'];
            $container['skipI18n'] = empty($item['skipI18n']) ? false : true;
            $container['route'] = empty($item['route']) ? false : $item['route'];
            $container['allowAll'] = empty($item['allowAll']) ? false : $item['allowAll'];
            $container['allowAny'] = empty($item['allowAny']) ? false : $item['allowAny'];
            $container['separator'] = empty($item['separator']) ? false : $item['separator'];
            $container['right'] = empty($item['right']) ? false : true;
        }
    }

    protected function getActive(array $item): string {
        return $this->isActive($item) ? ' active' : '';
    }

    protected function getLabel(array $item) {
        $user = $this->authenticationManager->getCurrentUser();
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $translate = $this->getView()->plugin('translate');

        switch ($item['text']) {
            case '%COMPANYNAME%':
                $text = $this->settingsManager->getSetting(SettingsManager::COMPANY_NAME);
                break;
            case '%USERNAME%':
                $text = $user->getDisplayName() ?? $user->getRealName();
                break;
            default:
                if (empty($item['skipI18n'])) {
                    $text = $translate($item['text']);
                } else {
                    $text = $item['text'];
                }
        }

        $icon = empty($item['icon']) ? '' : '<span class="if if-' . $escapeHtml($item['icon']) . '"></span>';

        return $icon . $escapeHtml($text);
    }

    protected function getChildren(array $item): array {
        if ($this->hasChildren($item)) {
            return $item['children'];
        }

        return [];
    }

    protected function getUrl(array $item): string {
        $url = $this->getView()->plugin('url');
        $route = $this->getRoute($item);

        return $url($route[0], $route[1], $route[2]);
    }

    protected function getRoute(array $item): array {
        if (empty($item['route'])) {
            return [null, [], []];
        }

        if (is_array($item['route'])) {
            $match = empty($item['route'][0]) ? null : $item['route'][0];
            $params = empty($item['route'][1]) ? [] : $item['route'][1];
            $query = empty($item['route'][2]) ? [] : $item['route'][2];
            return [$match, $params, $query];
        }

        return [$item['route'], [], []];
    }

    protected function hasChildren(array $item): bool {
        return !empty($item['children']);
    }

    protected function isActive(array $item): bool {
        if (!$this->routeMatch) {
            return false;
        }

        $currentMatch = $this->routeMatch->getMatchedRouteName();

        if ($currentMatch == $this->getRoute($item)[0]) {
            return true;
        }

        foreach ($this->getChildren($item) as $subItem) {
            if ($currentMatch == $this->getRoute($subItem)[0]) {
                return true;
            }
        }

        return false;
    }

    protected function isAllow(array $item): bool {
        if (!empty($item['allowAll'])) {
            foreach ($item['allowAll'] as $allow) {
                switch ($allow) {
                    case '%USER%':
                        if ($this->accessManager->currentUserIsGuest()) {
                            return false;
                        }
                        break;
                    case '%GUEST%':
                        if (!$this->accessManager->currentUserIsGuest()) {
                            return false;
                        }
                        break;
                    default:
                        if (!$this->accessManager->currentUserCan($allow)) {
                            return false;
                        }
                }
            }
            return true;
        }

        if (!empty($item['allowAny'])) {
            foreach ($item['allowAny'] as $allow) {
                switch ($allow) {
                    case '%USER%':
                        if (!$this->accessManager->currentUserIsGuest()) {
                            return true;
                        }
                        break;
                    case '%GUEST%':
                        if ($this->accessManager->currentUserIsGuest()) {
                            return true;
                        }
                        break;
                    default:
                        if ($this->accessManager->currentUserCan($allow)) {
                            return true;
                        }
                }
            }
        }

        return false;
    }

    protected function isLeft(array $item): bool {
        return empty($item['right']);
    }

    protected function isRight(array $item): bool {
        return !$this->isLeft($item);
    }

    protected function isSeparator(array $item): bool {
        return !empty($item['separator']);
    }

    protected function isBrand(string $key): bool {
        return $key == 'brand';
    }

    protected function renderItem(array $item) {
        $html = '';

        if ($this->isAllow($item)) {
            if ($this->hasChildren($item)) {
                $html .= '<li class="nav-item dropdown' . $this->getActive($item) . '">';
                $html .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $this->getLabel($item) . '</a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                foreach ($this->getChildren($item) as $subItem) {
                    if ($this->isAllow($subItem)) {
                        if ($this->isSeparator($subItem)) {
                            $html .= '<div class="dropdown-divider"></div>';
                        } else {
                            $html .= '<a class="dropdown-item' . $this->getActive($subItem) . '" href="' . $this->getUrl($subItem) . '">' . $this->getLabel($subItem) . '</a>';
                        }
                    }
                }
                $html .= '</div>';
                $html .= '</li>';
            } else {
                $html .= '<li class="nav-item' . $this->getActive($item) . '">';
                $html .= '<a class="nav-link" href="' . $this->getUrl($item) . '">' . $this->getLabel($item) . '</a>';
                $html .= '</li>';
            }
        }

        return $html;
    }

    public function render() {
        $html = '';

        $html .= '<nav class="navbar navbar-expand-md sticky-top navbar-light h6 pt-1 pb-1 shadow mb-2">';
        $html .= '<a class="navbar-brand" href="' . $this->getUrl($this->menu['brand']) . '">' . $this->getlabel($this->menu['brand']) . '</a>';
        $html .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
        $html .= '<span class="navbar-toggler-icon"></span>';
        $html .= '</button>';
        $html .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">';
        foreach ($this->menu as $key => $item) {
            if (!$this->isBrand($key)) {
                $html .= '<ul class="navbar-nav">';
                if ($this->isLeft($item)) {
                    $html .= $this->renderItem($item);
                }
                $html .= '</ul>';
            }
        }
        foreach ($this->menu as $item) {
            if (!$this->isBrand($key)) {
                $html .= '<ul class="navbar-nav ml-auto">';
                if ($this->isRight($item)) {
                    $html .= $this->renderItem($item);
                }
                $html .= '</ul>';
            }
        }
        $html .= '</div>';
        $html .= '</nav>';

        return $html;
    }

    public function __invoke(?array $menu = null) {
        if ($menu != null) {
            $this->addMenu($menu);
        }

        return $this;
    }

}
