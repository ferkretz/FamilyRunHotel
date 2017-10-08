<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RoomController extends AbstractActionController {

    public function __construct() {
        
    }

    public function indexAction() {
        
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
