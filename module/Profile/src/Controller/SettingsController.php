<?php

namespace Profile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\Site\CurrentOptionValueManager;
use Application\Service\User\CurrentUserEntityManager;
use Profile\Form\SettingsAccountForm;
use Profile\Form\SettingsLookForm;

class SettingsController extends AbstractActionController {

    /**
     * Current user manager.
     * @var CurrentUserManager
     */
    protected $currentUserEntityManager;

    /**
     * Current option value manager.
     * @var CurrentOptionValueManager
     */
    protected $currentOptionValueManager;

    public function __construct(CurrentUserEntityManager $currentUserEntityManager,
                                CurrentOptionValueManager $currentOptionValueManager) {
        $this->currentUserEntityManager = $currentUserEntityManager;
        $this->currentOptionValueManager = $currentOptionValueManager;
    }

    public function accountAction() {
        $form = new SettingsAccountForm();

        $currentUserEntity = $this->currentUserEntityManager->get();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $currentUserEntity->setEmail($data['email']);
                $currentUserEntity->setRealName($data['realName']);
                $currentUserEntity->setDisplayName(empty($data['displayName']) ? NULL : $data['displayName']);
                $currentUserEntity->setAddress($data['address']);
                $currentUserEntity->setPhone($data['phone']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $currentUserEntity->setPassword($bcrypt->create($data['password']));
                }
                $this->currentUserEntityManager->update();
            }
        } else {
            $data['email'] = $currentUserEntity->getEmail();
            $data['realName'] = $currentUserEntity->getRealName();
            $data['displayName'] = $currentUserEntity->getDisplayName();
            $data['address'] = $currentUserEntity->getAddress();
            $data['phone'] = $currentUserEntity->getPhone();
            $form->setData($data);
        }

        $this->layout()->activeMenuItemId = 'profileSettings';

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function lookAction() {
        $form = new SettingsLookForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $look = [
                    'theme' => $data['lookTheme'],
                    'renderHeader' => $data['lookRenderHeader'],
                    'barStyle' => $data['lookBarStyle'],
                ];
                $currentUserEntity = $this->currentUserEntityManager->get();
                $currentUserEntity->updateOptionValueByName('look', serialize($look));
                $this->currentUserEntityManager->update();

                return $this->redirect()->toRoute('profileSettings', ['action' => 'look']); // refresh because of theme change
            }
        } else {
            $look = $this->currentOptionValueManager->findOneByName('look');
            $data = [
                'lookTheme' => $look['theme'],
                'lookRenderHeader' => $look['renderHeader'],
                'lookBarStyle' => $look['barStyle'],
            ];
            $form->setData($data);
        }

        $this->layout()->activeMenuItemId = 'profileSettings';

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
