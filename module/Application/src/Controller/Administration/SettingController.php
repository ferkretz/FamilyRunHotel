<?php

namespace Application\Controller\Administration;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Setting\IndexForm;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;

class SettingController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(SettingsManager $settingsManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->accessManager = $accessManager;
    }

    public function indexAction() {
        $this->accessManager->currentUserRedirect('manage.settings');

        $form = new IndexForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $this->settingsManager->setOptionValue(SettingsManager::COMPANY_NAME, $data['companyName']);
                $this->settingsManager->setOptionValue(SettingsManager::COMPANY_FULL_NAME, $data['companyFullName']);
                $this->settingsManager->setOptionValue(SettingsManager::COMPANY_EMAIL, $data['companyEmail']);
                $this->settingsManager->setOptionValue(SettingsManager::COMPANY_ADDRESS, $data['companyAddress']);
                $this->settingsManager->setOptionValue(SettingsManager::COMPANY_PHONE, $data['companyPhone']);
                $this->settingsManager->setOptionValue(SettingsManager::CURRENCY, $data['currency']);
                $this->settingsManager->setOptionValue(SettingsManager::JPEG_QUALITY, $data['jpegQuality']);
                $this->settingsManager->setOptionValue(SettingsManager::PHOTO_MIN_SIZE, $data['photoMinSize']);
                $this->settingsManager->setOptionValue(SettingsManager::PHOTO_MAX_SIZE, $data['photoMaxSize']);

                return $this->redirect()->toRoute('admin-setting'); // refresh because of theme change
            }
        } else {
            $data = [
                'companyName' => $this->settingsManager->getSetting(SettingsManager::COMPANY_NAME),
                'companyFullName' => $this->settingsManager->getSetting(SettingsManager::COMPANY_FULL_NAME),
                'companyEmail' => $this->settingsManager->getSetting(SettingsManager::COMPANY_EMAIL),
                'companyAddress' => $this->settingsManager->getSetting(SettingsManager::COMPANY_ADDRESS),
                'companyPhone' => $this->settingsManager->getSetting(SettingsManager::COMPANY_PHONE),
                'currency' => $this->settingsManager->getSetting(SettingsManager::CURRENCY),
                'jpegQuality' => $this->settingsManager->getSetting(SettingsManager::JPEG_QUALITY),
                'photoMinSize' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MIN_SIZE),
                'photoMinSize' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MAX_SIZE),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
