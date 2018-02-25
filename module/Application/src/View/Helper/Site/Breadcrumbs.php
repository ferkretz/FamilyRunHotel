<?php

namespace Application\View\Helper\Site;

use Zend\View\Helper\AbstractHelper;

class Breadcrumbs extends AbstractHelper {

    protected $items;

    public function __construct() {
        $this->items = [];
    }

    public function setItems($items) {
        $this->items = $items;
    }

    public function render() {
        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $url = $this->getView()->plugin('url');
        $translate = $this->getView()->plugin('translate');

        $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        foreach ($this->items as $item) {
            if (isset($item['label'])) {
                if (isset($item['icon'])) {
                    $html .= '<strong><span class="glyphicon glyphicon-' . $item['icon'] . '"></span> ' . $escapeHtml($translate($item['label'])) . ': </strong>';
                } elseif (isset($item['active']) && $item['active'] == TRUE) {
                    $html .= '<li class="breadcrumb-item active" aria-current="page">' . $escapeHtml($translate($item['label'])) . '</li>';
                } elseif (isset($item['url'])) {
                    $html .= '<li class="breadcrumb-item"><a href="' . $url($item['url'][0], $item['url'][1]) . '">' . $escapeHtml($translate($item['label'])) . '</a></li>';
                } else {
                    $html .= '<li class="breadcrumb-item">' . $escapeHtml($translate($item['label'])) . '</li>';
                }
            }
        }
        $html .= '</ol></nav>';

        return $html;
    }

}
