<?php

namespace Application\Model\Site;

class MenuItemModel {

    protected $parent;
    protected $id;
    protected $separator;
    protected $align;
    protected $label;
    protected $i18n;
    protected $route;
    protected $action;
    protected $icon;
    protected $render;
    protected $active;
    protected $children;

    public function __construct($parent) {
        $this->parent = $parent;
        $this->children = [];
    }

    public function getParent() {
        return $this->parent;
    }

    public function getId() {
        return $this->id;
    }

    public function isSeparator() {
        return $this->separator;
    }

    public function getAlign() {
        return $this->align;
    }

    public function getLabel() {
        return $this->label;
    }

    public function isI18n() {
        return $this->i18n;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getAction() {
        return $this->action;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getRender() {
        return $this->render;
    }

    public function isActive() {
        return $this->active;
    }

    public function getChildren() {
        return $this->children;
    }

    public function hasChildren() {
        return !empty($this->children);
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSeparator($separator) {
        $this->separator = $separator;
    }

    public function setAlign($align) {
        $this->align = $align;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function setI18n($i18n) {
        $this->i18n = $i18n;
    }

    public function setRoute($route) {
        $this->route = $route;
    }

    public function setAction($action) {
        $this->action = $action;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function setRender($render) {
        $this->render = $render;
    }

    public function setActive($active) {
        $this->active = $active;
        if ($this->parent) {
            $this->parent->setActive($active);
        }
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

    public function addChild($child) {
        $this->children[] = $child;
    }

}
