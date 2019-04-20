<?php

namespace Application\Controller\Profile;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Profile\Password\IndexForm;
use Application\Service\User\AccessManager;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserManager;

class PasswordController extends AbstractActionController {

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

        $user = $this->userManager->findOneById($this->authenticationManager->getCurrentUser()->getId());

        $form = new IndexForm($user->getPassword());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $bcrypt = new Bcrypt();
                $user->setPassword($bcrypt->create($data['password']));
                $this->userManager->update();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
