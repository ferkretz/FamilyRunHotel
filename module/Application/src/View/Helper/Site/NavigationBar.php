<?php

namespace Application\View\Helper\Site;

use Zend\View\Helper\AbstractHelper;
use Application\Model\Site\MenuModel;
use Application\Model\Site\MenuItemModel;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\CurrentUserEntityManager;

class NavigationBar extends AbstractHelper {

    protected $menuModel;
    protected $authenticationManager;
    protected $currentUserEntityManager;
    protected $lookConfig;
    protected $companyConfig;
    protected $activeMenuItemId;

    public function __construct(MenuModel $menuModel,
                                AuthenticationManager $authenticationManager,
                                CurrentUserEntityManager $currentUserEntityManager,
                                $lookConfig,
                                $companyConfig) {
        $this->menuModel = $menuModel;
        $this->authenticationManager = $authenticationManager;
        $this->currentUserEntityManager = $currentUserEntityManager;
        $this->lookConfig = $lookConfig;
        $this->companyConfig = $companyConfig;
        $this->activeMenuItemId = NULL;
    }

    public function setActiveMenuItemId($activeMenuItemId = NULL) {
        if ($activeMenuItemId) {
            $this->activeMenuItemId = $activeMenuItemId;
        }
    }

    public function render() {
        if ($this->activeMenuItemId) {
            $this->menuModel->activateChild($this->activeMenuItemId);
        }

        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $url = $this->getView()->plugin('url');
        $translate = $this->getView()->plugin('translate');
        $label = $this->companyConfig['i18n'] ? $translate($this->companyConfig['name']) : $this->companyConfig['name'];

        $html = '<nav class="navbar navbar-' . $escapeHtml($this->lookConfig['barStyle']) . ' navbar-fixed-top" role="navigation">'
                . '<div class="container" >'
                . '<div class="navbar-header">'
                . '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">'
                . '<span class="sr-only">Toggle navigation</span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '</button>'
                . '<a class="navbar-brand" href="' . $url('homeIndex') . '">' . $escapeHtml($label) . '</a>'
                . '</div>'
                . '<div id="navbar" class="collapse navbar-collapse">'
                . '<ul class="nav navbar-nav">';

        foreach ($this->menuModel->getChildren() as $child) {
            if ($child->getAlign() == 'left') {
                if ($this->authenticationManager->filterAccessMenuItem($child->getId()) == AuthenticationManager::ACCESS_GRANTED) {
                    $html .= $this->renderItem($child);
                }
            }
        }
        $html .= '</ul>'
                . '<ul class="nav navbar-nav navbar-right">';
        foreach ($this->menuModel->getChildren() as $child) {
            if ($child->getAlign() == 'right') {
                if ($this->authenticationManager->filterAccessMenuItem($child->getId()) == AuthenticationManager::ACCESS_GRANTED) {
                    $html .= $this->renderItem($child);
                }
            }
        }
        $html .= '</ul>'
                . '</div>'
                . '</div>'
                . '</nav>';

        return $html;
    }

    protected function renderItem(MenuItemModel $item) {
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $url = $this->getView()->plugin('url');
        $translate = $this->getView()->plugin('translate');

        if ($item->getLabel() == '%username%') {
            $currentUserEntity = $this->currentUserEntityManager->get();
            $label = $escapeHtml($currentUserEntity->getDisplayName() ?? $currentUserEntity->getRealName());
        } else {
            $label = $item->getLabel() ? $escapeHtml($item->isI18n() ? $translate($item->getLabel()) : $item->getLabel()) : '';
        }
        $icon = $item->getIcon() ? '<span class="glyphicon glyphicon-' . $escapeHtml($item->getIcon()) . '"></span> ' : '';
        $link = $url($item->getRoute(), is_array($item->getAction()) ? $item->getAction() : []);

        $html = '';

        if ($item->hasChildren()) {
            $html .= '<li class="dropdown ' . ($item->isActive() ? 'active' : '') . '">'
                    . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'
                    . $icon . $label . ' <b class="caret"></b>'
                    . '</a>'
                    . '<ul class="dropdown-menu">';
            foreach ($item->getChildren() as $child) {
                if ($child->isSeparator()) {
                    $html .= '<li role="separator" class="divider"></li>';
                } else {
                    $label = $child->getLabel() ? $escapeHtml($child->isI18n() ? $translate($child->getLabel()) : $child->getLabel()) : '';
                    $icon = $child->getIcon() ? '<span class="glyphicon glyphicon-' . $escapeHtml($child->getIcon()) . '"></span> ' : '';
                    $link = $url($child->getRoute(), is_array($child->getAction()) ? $child->getAction() : []);

                    $html .= ($child->isActive() ? '<li class="active">' : '<li>')
                            . '<a href="' . $link . '">' . $icon . $label . '</a>'
                            . '</li>';
                }
            }
            $html .= '</ul>'
                    . '</li>';
        } else {

            $html .= ($item->isActive() ? '<li class="active">' : '<li>')
                    . '<a href="' . $link . '">' . $icon . $label . '</a>'
                    . '</li>';
        }

        return $html;
    }

}
