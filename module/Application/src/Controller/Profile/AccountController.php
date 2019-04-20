<?php

namespace Application\Controller\Profile;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Profile\Account\IndexForm;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserManager;

class AccountController extends AbstractActionController {

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

    /**
     * User entity manager.
     * @var UserManager
     */
    protected $userEntityManager;

    public function __construct(AccessManager $accessManager,
                                AuthenticationManager $authenticationManager,
                                UserManager $userManager) {
        $this->accessManager = $accessManager;
        $this->authenticationManager = $authenticationManager;
        $this->userManager = $userManager;
    }

    public function indexAction() {
        $this->accessManager->currentUserRedirect('manage.profile');

        $form = new IndexForm();

        $user = $this->userManager->findOneById($this->authenticationManager->getCurrentUser()->getId());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $user->setEmail($data['email']);
                $user->setRealName($data['realName']);
                $user->setDisplayName(empty($data['displayName']) ? NULL : $data['displayName']);
                $user->setAddress($data['address']);
                $user->setPhone($data['phone']);
                $this->userManager->update();
            }
        } else {
            $data['email'] = $user->getEmail();
            $data['realName'] = $user->getRealName();
            $data['displayName'] = $user->getDisplayName();
            $data['address'] = $user->getAddress();
            $data['phone'] = $user->getPhone();
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
