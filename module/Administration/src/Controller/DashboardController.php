<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Form\DashboardCompanyForm;
use Administration\Form\DashboardGoogleForm;
use Administration\Form\DashboardLookForm;
use Application\Entity\SiteOption;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;

class DashboardController extends AbstractActionController {

    /**
     * Picture manager.
     * @var OptionManager
     */
    protected $optionManager;

    /**
     * Theme selector
     * @var ThemeSelector
     */
    protected $themeSelector;

    public function __construct(SiteOptionManager $optionManager,
                                ThemeSelector $themeSelector) {
        $this->optionManager = $optionManager;
        $this->themeSelector = $themeSelector;
    }

    public function companyAction() {
        $form = new DashboardCompanyForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $this->saveOption('brandName', $data['brandName']);
                $this->saveOption('email', $data['email']);
                $this->saveOption('address', $data['address']);
                $this->saveOption('phone', $data['phone']);

                return $this->redirect()->toRoute('administrationDashboard', ['action' => 'look']); // refresh because of theme change
            }
        } else {
            $data['brandName'] = $this->optionManager->findValueByName('brandName');
            $data['email'] = $this->optionManager->findValueByName('email');
            $data['address'] = $this->optionManager->findValueByName('address');
            $data['phone'] = $this->optionManager->findValueByName('phone');
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('administrationDashboard');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function googleAction() {
        $form = new DashboardGoogleForm();

        $this->layout()->navBarData->setActiveItemId('administrationDashboard');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $this->saveOption('latitude', str_replace(localeconv()['decimal_point'], '.', $data['latitude']));
                $this->saveOption('longitude', str_replace(localeconv()['decimal_point'], '.', $data['longitude']));
                $this->saveOption('zoom', $data['zoom']);

                return $this->redirect()->toRoute('administrationDashboard', ['action' => 'google']); // refresh because of theme change
            }
        } else {
            $data['latitude'] = number_format($this->optionManager->findValueByName('latitude'), 7, localeconv()['decimal_point'], '');
            $data['longitude'] = number_format($this->optionManager->findValueByName('longitude'), 7, localeconv()['decimal_point'], '');
            $data['zoom'] = $this->optionManager->findValueByName('zoom');
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function lookAction() {
        $form = new DashboardLookForm($this->themeSelector->getSupportedThemeNames());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $this->saveOption('theme', $data['theme']);
                $this->saveOption('navBarStyle', $data['navBarStyle']);
                $this->saveOption('headerShow', $data['headerShow']);

                return $this->redirect()->toRoute('administrationDashboard', ['action' => 'look']); // refresh because of theme change
            }
        } else {
            $data['theme'] = $this->optionManager->findValueByName('theme');
            $data['navBarStyle'] = $this->optionManager->findValueByName('navBarStyle');
            $data['headerShow'] = $this->optionManager->findValueByName('headerShow');
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('administrationDashboard');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    protected function saveOption($name,
                                  $data) {
        $option = $this->optionManager->findByName($name);
        if ($option) {
            $option->setValue($data);
            $this->optionManager->update();
        } else {
            $option = new SiteOption();
            $option->setName($name);
            $option->setValue($data);
            $this->optionManager->add($option);
        }
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
