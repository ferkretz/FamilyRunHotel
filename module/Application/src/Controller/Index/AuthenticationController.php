<?php

namespace Application\Controller\Index;

use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;
use Application\Service\User\AuthenticationManager;
use Application\Form\Index\LoginForm;

class AuthenticationController extends AbstractActionController {

    /**
     * Authentication manager.
     * @var AuthenticationManager
     */
    protected $authenticationManager;

    public function __construct(AuthenticationManager $authenticationManager) {
        $this->authenticationManager = $authenticationManager;
    }

    public function loginAction() {
        if ($this->authenticationManager->getCurrentUser()) {
            return $this->redirect()->toRoute('index');
        }

        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new LoginForm();
        $form->get('redirectUrl')->setValue($redirectUrl);

        $isLoginError = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->authenticationManager->login($data['email'], $data['password'], $data['rememberMe']);

                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirectUrl', '');
                    if (!empty($redirectUrl)) {
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != true) {
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }
                    }
                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('index');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
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

        return $this->redirect()->toRoute('index');
    }

    public function registerAction() {
        
    }

    public function resetPasswordAction() {
        
    }

}
