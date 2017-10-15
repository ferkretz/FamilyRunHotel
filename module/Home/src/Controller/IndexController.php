<?php

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\SiteOptionManager;
use Application\Model\NavBarData;

class IndexController extends AbstractActionController {

    protected $optionManager;
    protected $navBarData;

    public function __construct(SiteOptionManager $optionManager,
                                NavBarData $navBarData) {
        $this->optionManager = $optionManager;
        $this->navBar = $navBarData;
    }

    public function indexAction() {
        $address = $this->optionManager->findValueByName('address');
        $email = $this->optionManager->findValueByName('email');
        $phone = $this->optionManager->findValueByName('phone');

        $latitude = $this->optionManager->findValueByName('latitude');
        $longitude = $this->optionManager->findValueByName('longitude');
        $zoom = $this->optionManager->findValueByName('zoom');

        $this->layout()->navBarData->setActiveItemId('homeIndex');
        if ($this->optionManager->findCurrentValueByName('headerShow') != 'nowhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

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
        $this->layout()->navBarData->setActiveItemId('homeRoom');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }
    }

    public function visitorsBookAction() {
        $this->layout()->navBarData->setActiveItemId('homeVisitorsBook');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
