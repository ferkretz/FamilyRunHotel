<?php

namespace Application\Model;

class NavBarData {

    protected $style;
    protected $brandName;
    protected $brandLink;
    protected $menuItems = [];
    protected $activeItemId;

    function getStyle() {
        return $this->style;
    }

    function getBrandName() {
        return $this->brandName;
    }

    function getBrandLink() {
        return $this->brandLink;
    }

    function getMenuItems() {
        return $this->menuItems;
    }

    function getActiveItemId() {
        return $this->activeItemId;
    }

    function setStyle($style) {
        $this->style = $style;
    }

    function setBrandName($brandName) {
        $this->brandName = $brandName;
    }

    function setBrandLink($brandLink) {
        $this->brandLink = $brandLink;
    }

    function setMenuItems($menuItems) {
        $this->menuItems = $menuItems;
    }

    function setActiveItemId($activeItemId) {
        $this->activeItemId = $activeItemId;

        if (isset($this->menuItems[$activeItemId])) {
            $this->menuItems[$activeItemId]['active'] = 'true';
            return;
        }

        foreach ($this->menuItems as $key => $value) {
            if (isset($value['dropdown'])) {
                if (isset($value['dropdown'][$activeItemId])) {
                    $this->menuItems[$key]['dropdown'][$activeItemId]['active'] = TRUE;
                    $this->menuItems[$key]['active'] = TRUE;
                    return;
                }
            }
        }
    }

    public function addMenuItem($item) {
        if (!isset($item['id'])) {
            return;
        }

        if (isset($item['parent_id'])) {
            if (!isset($this->menuItems[$item['parent_id']])) {
                return;
            }
            $this->menuItems[$item['parent_id']]['dropdown'][$item['id']] = $item;
        } else {
            $this->menuItems[$item['id']] = $item;
        }
    }

}
