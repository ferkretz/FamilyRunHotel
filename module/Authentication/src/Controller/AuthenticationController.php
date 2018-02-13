<?php

namespace Authentication\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;
use Application\Service\User\AuthenticationManager;
use Application\Service\User\UserEntityManager;
use Authentication\Form\LoginForm;

class AuthenticationController extends AbstractActionController {

    /**
     * Authentication manager.
     * @var AuthenticationManager
     */
    protected $authenticationManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    protected $authenticationService;

    /**
     * User entity manager.
     * @var UserEntityManager
     */
    protected $userEntityManager;

    public function __construct(AuthenticationManager $authenticationManager,
                                AuthenticationService $authenticationService,
                                UserEntityManager $userEntityManager) {
        $this->authenticationManager = $authenticationManager;
        $this->authenticationService = $authenticationService;
        $this->userEntityManager = $userEntityManager;
    }

    public function registerAction() {
        
    }

    public function loginAction() {
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = FALSE;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->authenticationManager->login($data['email'], $data['password'], $data['remember_me']);

                if ($result->getCode() == Result::SUCCESS) {

                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != NULL) {
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }
                    }

                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('homeIndex');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = TRUE;
                }
            } else {
                $isLoginError = TRUE;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function logoutAction() {
        $this->authenticationManager->logout();

        return $this->redirect()->toRoute('homeIndex');
    }

    public function resetPasswordAction() {
        
    }

}
