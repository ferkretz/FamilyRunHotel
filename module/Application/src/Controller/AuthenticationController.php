<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;
use Administration\Service\UserManager;
use Application\Form\LoginForm;
use Application\Service\AuthenticationManager;

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
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * User manager.
     * @var UserManager
     */
    protected $userManager;

    public function __construct(AuthenticationManager $authenticationManager,
                                AuthenticationService $authenticationService,
                                EntityManager $entityManager,
                                UserManager $userManager) {
        $this->authenticationManager = $authenticationManager;
        $this->authenticationService = $authenticationService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
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
                        if (!$uri->isValid() || $uri->getHost() != null) {
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }
                    }

                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
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

        return $this->redirect()->toRoute('home');
    }

    public function notAuthorizedAction() {
        $this->getResponse()->setStatusCode(403);

        return new ViewModel();
    }

    public function resetPasswordAction() {
    }

}
