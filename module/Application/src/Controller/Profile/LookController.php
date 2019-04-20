<?php

namespace Application\Controller\Profile;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Profile\Look\IndexForm;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;

class LookController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * User access manager.
     * @var AccessManager
     */
    protected $accessManager;

    /**
     * AuthenticationManager.
     * @var AuthenticationManager
     */
    protected $authenticationManager;

    public function __construct(SettingsManager $settingsManager,
                                AccessManager $accessManager,
                                AuthenticationManager $authenticationManager) {
        $this->settingsManager = $settingsManager;
        $this->accessManager = $accessManager;
        $this->authenticationManager = $authenticationManager;
    }

    public function indexAction() {
        $this->accessManager->currentUserRedirect('manage.profile');

        $form = new IndexForm($this->settingsManager);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $userId = $this->authenticationManager->getCurrentUser()->getId();
                $this->settingsManager->setOptionValue(SettingsManager::THEME, $data['theme'], $userId);
                $this->settingsManager->setOptionValue(SettingsManager::BAR_STYLE, $data['barStyle'], $userId);

                return $this->redirect()->toRoute('profile-look'); // refresh because of theme change
            }
        } else {
            $data = [
                'theme' => $this->settingsManager->getSetting(SettingsManager::THEME),
                'barStyle' => $this->settingsManager->getSetting(SettingsManager::BAR_STYLE),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
