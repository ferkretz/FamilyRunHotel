<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class NavBar extends AbstractHelper {

    /**
     * Menu items array.
     * @var array
     */
    protected $navBarElements;

    /**
     * Active item's ID.
     * @var string
     */
    protected $activeItemId = '';

    public function __construct($navBarElements) {
        $this->navBarElements = $navBarElements;
    }

    /**
     * Sets ID of the active items.
     * @param string $activeItemId
     */
    public function setActiveItemId($activeItemId) {
        $this->activeItemId = $activeItemId;
    }

    /**
     * Renders the navigation bar.
     * @return string HTML code of the menu.
     */
    public function render() {
        $escapeHtml = $this->getView()->plugin('escapeHtml');

        $result = '<nav class="navbar navbar-' . $escapeHtml($this->navBarElements['options']['navbar_style']) . ' navbar-fixed-top" role="navigation">'
                . '<div class="container" >'
                . '<div class="navbar-header">'
                . '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">'
                . '<span class="sr-only">Toggle navigation</span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '</button>'
                . '<a class="navbar-brand" href="' . $escapeHtml($this->navBarElements['options']['brand_link']) . '">' . $escapeHtml($this->navBarElements['options']['brand_name']) . '</a>'
                . '</div>'
                . '<div id="navbar" class="collapse navbar-collapse">'
                . '<ul class="nav navbar-nav">';
        foreach ($this->navBarElements['menu_items'] as $item) {
            if (!isset($item['float']) || $item['float'] == 'left') {
                $result .= $this->renderItem($item);
            }
        }
        $result .= '</ul>'
                . '<ul class="nav navbar-nav navbar-right">';
        foreach ($this->navBarElements['menu_items'] as $item) {
            if (isset($item['float']) && $item['float'] == 'right') {
                $result .= $this->renderItem($item);
            }
        }
        $result .= '</ul>'
                . '</div>'
                . '</div>'
                . '</nav>';

        return $result;
    }

    /**
     * Renders an item.
     * @param array $item The menu item info.
     * @return string HTML code of the item.
     */
    protected function renderItem($item) {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id == $this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';

        $result = '';

        $escapeHtml = $this->getView()->plugin('escapeHtml');

        if (isset($item['dropdown'])) {
            $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';
            $dropdownItems = $item['dropdown'];

            $result .= '<li class="dropdown ' . ($isActive ? 'active' : '') . '">'
                    . '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'
                    . $glyphicon . $escapeHtml($label) . ' <b class="caret"></b>'
                    . '</a>'
                    . '<ul class="dropdown-menu">';
            foreach ($dropdownItems as $item) {
                if (isset($item['separator']) && $item['separator'] == TRUE) {
                    $result .= '<li role="separator" class="divider"></li>';
                } else {
                    $link = isset($item['link']) ? $item['link'] : '#';
                    $label = isset($item['label']) ? $item['label'] : '';
                    $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';

                    $result .= '<li>'
                            . '<a href="' . $escapeHtml($link) . '">' . $glyphicon . $escapeHtml($label) . '</a>'
                            . '</li>';
                }
            }
            $result .= '</ul>'
                    . '</li>';
        } else {
            $link = isset($item['link']) ? $item['link'] : '#';
            $glyphicon = isset($item['glyphicon']) ? '<span class="glyphicon glyphicon-' . $escapeHtml($item['glyphicon']) . '"></span> ' : '';

            $result .= $isActive ? '<li class="active">' : '<li>'
                    . '<a href="' . $escapeHtml($link) . '">' . $glyphicon . $escapeHtml($label) . '</a>'
                    . '</li>';
        }

        return $result;
    }

}
