<?php

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\Site\SiteOptionValueManager;

class IndexController extends AbstractActionController {

    /**
     * Site option value manager.
     * @var SiteOptionValueManager
     */
    protected $siteOptionValueManager;

    public function __construct(SiteOptionValueManager $siteOptionValueManager) {
        $this->siteOptionValueManager = $siteOptionValueManager;
    }

    public function indexAction() {
        // google
        $defaultGoogle = [
            'enable' => FALSE,
            'latitude' => 0,
            'longitude' => 0,
            'zoom' => 15,
        ];
        $google = $this->siteOptionValueManager->findOneByName('google', $defaultGoogle);
        // company
        $defaultCompany = [
            'name' => 'Family-run Hotel',
            'i18n' => FALSE,
            'fullName' => 'Family-run Hotel Inc.',
            'email' => NULL,
            'address' => NULL,
            'phone' => NULL,
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        $this->layout()->activeMenuItemId = 'homeIndex';

        return new ViewModel([
            'google' => $google,
            'company' => $company,
        ]);
    }
    
    public function test() {
        
    }

}
