<?php

namespace Application\Controller\Administration;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Look\IndexForm;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;

class LookController extends AbstractActionController {

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

        $form = new IndexForm($this->settingsManager);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $this->settingsManager->setOptionValue(SettingsManager::THEME, $data['theme']);
                $this->settingsManager->setOptionValue(SettingsManager::BAR_STYLE, $data['barStyle']);
                $this->settingsManager->setOptionValue(SettingsManager::ENABLE_MAP, $data['enableMap']);
                $this->settingsManager->setOptionValue(SettingsManager::MAP_ZOOM, $data['mapZoom']);
                $this->settingsManager->setOptionValue(SettingsManager::ROWS_PER_PAGE, $data['rowsPerPage']);
                $this->settingsManager->setOptionValue(SettingsManager::PHOTOS_PER_PAGE, $data['photosPerPage']);
                $this->settingsManager->setOptionValue(SettingsManager::ROOMS_PER_PAGE, $data['roomsPerPage']);
                $this->settingsManager->setOptionValue(SettingsManager::PHOTO_THUMBNAIL_WIDTH, $data['photoThumbnailWidth']);

                return $this->redirect()->toRoute('admin-look'); // refresh because of theme change
            }
        } else {
            $data = [
                'theme' => $this->settingsManager->getSetting(SettingsManager::THEME),
                'barStyle' => $this->settingsManager->getSetting(SettingsManager::BAR_STYLE),
                'enableMap' => $this->settingsManager->getSetting(SettingsManager::ENABLE_MAP),
                'mapZoom' => $this->settingsManager->getSetting(SettingsManager::MAP_ZOOM),
                'rowsPerPage' => $this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE),
                'photosPerPage' => $this->settingsManager->getSetting(SettingsManager::PHOTOS_PER_PAGE),
                'roomsPerPage' => $this->settingsManager->getSetting(SettingsManager::ROOMS_PER_PAGE),
                'photoThumbnailWidth' => $this->settingsManager->getSetting(SettingsManager::PHOTO_THUMBNAIL_WIDTH),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
