<?php

namespace Application\Model;

class HeaderData {

    protected $brandName;
    protected $visible = FALSE;

    function getBrandName() {
        return $this->brandName;
    }

    function getVisible(): bool {
        return $this->visible;
    }

    function setBrandName($brandName) {
        $this->brandName = $brandName;
    }

    function setVisible(bool $visible) {
        $this->visible = $visible;
    }

}
