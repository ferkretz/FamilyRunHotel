<?php

namespace Application\Model\Site;

class MenuModel {

    protected $children;

    public function __construct() {
        $this->children = [];
    }

    public function getChildren() {
        return $this->children;
    }

    public function hasChildren() {
        return !empty($this->children);
    }

    public function activateChild($activeMenuItemId) {
        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                if ($child->getId() == $activeMenuItemId) {
                    $child->setActive(TRUE);
                    return TRUE;
                } else {
                    if ($child->activateChild($activeMenuItemId)) {
                        return TRUE;
                    }
                }
            }
        }

        return FALSE;
    }

    public function addChild(MenuItemModel $child) {
        $this->children[] = $child;
    }

    public function parseMenuData($elements,
                                  $parent = NULL) {
        foreach ($elements as $element) {
            if (isset($element['label']) || isset($element['icon'])) {
                $item = new MenuItemModel($parent);
                foreach ($element as $key => $value) {
                    switch ($key) {
                        case 'label':
                            $item->setLabel($value);
                            break;
                        case 'id':
                            $item->setId($value);
                            break;
                        case 'i18n':
                            $item->setI18n($value);
                            break;
                        case 'route':
                            $item->setRoute($value);
                            break;
                        case 'action':
                            $item->setAction($value);
                            break;
                        case 'icon':
                            $item->setIcon($value);
                            break;
                        case 'render':
                            $item->setRender($value);
                            break;
                        case 'active':
                            $item->setActive($value);
                            break;
                    }
                }
                if ($parent) {
                    $parent->addChild($item);
                } else {
                    $this->addChild($item);
                    $item->setAlign(isset($element['align']) ? $element['align'] : 'left');
                }
                if (isset($element['children'])) {
                    $this->parseMenuData($element['children'], $item);
                }
            } else {
                if (isset($element['separator']) && $element['separator'] == TRUE && $parent) {
                    $item = new MenuItemModel($parent);
                    $item->setSeparator(TRUE);
                    $parent->addChild($item);
                }
            }
        }
    }

}
