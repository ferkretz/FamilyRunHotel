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
        $googleMap = $this->optionManager->findByName('google_map');
        if ($googleMap) {
            $googleMap = unserialize($googleMap);
        }

        $hotel = [];
        $address = $this->optionManager->findByName('hotel_address');
        if ($address) {
            $hotel['address'] = $address;
        }
        $email = $this->optionManager->findByName('hotel_email');
        if ($email) {
            $hotel['email'] = $email;
        }
        $phone = $this->optionManager->findByName('hotel_phone');
        if ($phone) {
            $hotel['phone'] = $phone;
        }

        return new ViewModel([
            'googleMap' => $googleMap,
            'hotel' => $hotel,
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
