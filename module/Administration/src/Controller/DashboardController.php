<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Entity\Options;
use Administration\Form\DashboardIndexForm;
use Administration\Service\OptionManager;

class DashboardController extends AbstractActionController {

    /**
     * Picture manager.
     * @var OptionManager
     */
    protected $optionManager;

    public function __construct(OptionManager $optionManager) {
        $this->optionManager = $optionManager;
    }

    public function indexAction() {
        $form = new DashboardIndexForm();
        
        return new ViewModel([
            'form' => $form,
        ]);
        
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
