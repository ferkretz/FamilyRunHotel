<?php

namespace Profile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SiteOption;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;
use Authentication\Service\CurrentUserManager;
use Profile\Form\SettingsAccountForm;
use Profile\Form\SettingsLookForm;

class SettingsController extends AbstractActionController {

    /**
     * Current User manager.
     * @var CurrentUserManager
     */
    protected $currentUserManager;

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

    public function __construct(CurrentUserManager $currentUserManager,
                                SiteOptionManager $optionManager,
                                ThemeSelector $themeSelector) {
        $this->currentUserManager = $currentUserManager;
        $this->optionManager = $optionManager;
        $this->themeSelector = $themeSelector;
    }

    public function accountAction() {
        $form = new SettingsAccountForm();

        $currentUser = $this->currentUserManager->get();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $currentUser->setEmail($data['email']);
                $currentUser->setRealName($data['realName']);
                if (!empty($data['displayName'])) {
                    $currentUser->setDisplayName($data['displayName']);
                }
                $currentUser->setAddress($data['address']);
                $currentUser->setPhone($data['phone']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $currentUser->setPassword($bcrypt->create($data['password']));
                }
                $this->currentUserManager->update();
            }
        } else {
            $data['email'] = $currentUser->getEmail();
            $data['realName'] = $currentUser->getRealName();
            $data['diaplayName'] = $currentUser->getDisplayName();
            $data['address'] = $currentUser->getAddress();
            $data['phone'] = $currentUser->getPhone();
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('profileSettings');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function lookAction() {
        $form = new SettingsLookForm($this->themeSelector->getSupportedThemeNames());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $currentUser = $this->currentUserManager->get();
                $currentUser->setOptionValue('theme', $data['theme']);
                $currentUser->setOptionValue('navBarStyle', $data['navBarStyle']);
                $currentUser->setOptionValue('headerShow', $data['headerShow']);
                $this->currentUserManager->update();

                return $this->redirect()->toRoute('profileSettings', ['action' => 'look']); // refresh because of theme change
            }
        } else {
            $data['theme'] = $this->optionManager->findCurrentValueByName('theme');
            $data['navBarStyle'] = $this->optionManager->findCurrentValueByName('navBarStyle');
            $data['headerShow'] = $this->optionManager->findCurrentValueByName('headerShow');
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('profileSettings');
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
