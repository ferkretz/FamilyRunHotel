<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Model\NavBarData;

class NavBar extends AbstractHelper {

    public function __invoke(NavBarData $navBarData = NULL) {
        if ($navBarData) {
            return $this->render($navBarData);
        }

        return $this;
    }

    public function render(NavBarData $navBarData) {
        $escapeHtml = $this->getView()->plugin('escapeHtml');

        $html = '<nav class="navbar navbar-' . $escapeHtml($navBarData->getStyle()) . ' navbar-fixed-top" role="navigation">'
                . '<div class="container" >'
                . '<div class="navbar-header">'
                . '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">'
                . '<span class="sr-only">Toggle navigation</span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '</button>'
                . '<a class="navbar-brand" href="' . $escapeHtml($navBarData->getBrandLink()) . '">' . $escapeHtml($navBarData->getBrandName()) . '</a>'
                . '</div>'
                . '<div id="navbar" class="collapse navbar-collapse">'
                . '<ul class="nav navbar-nav">';
        foreach ($navBarData->getMenuItems() as $item) {
            if (!isset($item['float']) || $item['float'] == 'left') {
                $html .= $this->renderItem($item);
            }
        }
        $html .= '</ul>'
                . '<ul class="nav navbar-nav navbar-right">';
        foreach ($navBarData->getMenuItems() as $item) {
            if (isset($item['float']) && $item['float'] == 'right') {
                $html .= $this->renderItem($item);
            }
        }
        $html .= '</ul>'
                . '</div>'
                . '</div>'
                . '</nav>';

        return $html;
    }

    protected function renderItem($item) {
        if (!isset($item['grant']) || !$item['grant']) {
            return '';
        }
        if (!isset($item['id'])) {
            return '';
        }
        if ((!isset($item['label'])) && (!isset($item['glyphicon']))) {
            return '';
        }

        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $label = isset($item['label']) ? $escapeHtml($item['label']) : '';
        $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';

        $html = '';

        if (isset($item['dropdown'])) {
            $dropdownItems = $item['dropdown'];

            $html .= '<li class="dropdown ' . (isset($item['active']) ? 'active' : '') . '">'
                    . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'
                    . $glyphicon . $label . ' <b class="caret"></b>'
                    . '</a>'
                    . '<ul class="dropdown-menu">';
            foreach ($dropdownItems as $item) {
                if (isset($item['separator'])) {
                    $html .= '<li role="separator" class="divider"></li>';
                } else {
                    $link = isset($item['link']) ? $escapeHtml($item['link']) : '#';
                    $label = isset($item['label']) ? $escapeHtml($item['label']) : '';
                    $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';

                    $html .= (isset($item['active']) ? '<li class="active">' : '<li>')
                            . '<a href="' . $link . '">' . $glyphicon . $label . '</a>'
                            . '</li>';
                }
            }
            $html .= '</ul>'
                    . '</li>';
        } else {
            $link = isset($item['link']) ? $escapeHtml($item['link']) : '#';
            $label = isset($item['label']) ? $escapeHtml($item['label']) : '';
            $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';

            $html .= (isset($item['active']) ? '<li class="active">' : '<li>')
                    . '<a href="' . $link . '">' . $glyphicon . $label . '</a>'
                    . '</li>';
        }

        return $html;
    }

}
