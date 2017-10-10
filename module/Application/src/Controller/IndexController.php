<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Service\OptionManager;

class IndexController extends AbstractActionController {

    protected $optionManager;

    public function __construct(OptionManager $optionManager) {
        $this->optionManager = $optionManager;
    }

    public function indexAction() {
        $address = $this->optionManager->findByName('address');
        $email = $this->optionManager->findByName('email');
        $phone = $this->optionManager->findByName('phone');

        $latitude = $this->optionManager->findByName('latitude');
        $longitude = $this->optionManager->findByName('longitude');
        $zoom = $this->optionManager->findByName('zoom');

        return new ViewModel([
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'zoom' => $zoom,
        ]);
    }

    public function roomsAction() {
        
    }

    public function visitorsBookAction() {
        
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
