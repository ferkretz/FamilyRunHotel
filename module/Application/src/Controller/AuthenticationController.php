<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Entity\User;
use Application\Service\AuthenticationManager;
use Application\Service\UserManager;
use Zend\Log\Logger;

class AuthenticationController extends AbstractActionController {

    /**
     * Authentication manager.
     * @var AuthenticationManager
     */
    private $authenticationManager;

    /**
     * Authentication service.
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * User manager.
     * @var UserManager
     */
    private $userManager;

    public function __construct(AuthenticationManager $authenticationManager,
                                AuthenticationService $authenticationService,
                                EntityManager $entityManager,
                                UserManager $userManager) {
        $this->authenticationManager = $authenticationManager;
        $this->authenticationService = $authenticationService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function loginAction() {
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        // Store login status.
        $isLoginError = false;

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Perform login attempt.
                $result = $this->authenticationManager->login($data['email'], $data['password'], $data['remember_me']);

                // Check result.
                if ($result->getCode() == Result::SUCCESS) {

                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // The below check is to prevent possible redirect attack
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
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

    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction() {
        $this->authenticationManager->logout();

        return $this->redirect()->toRoute('home');
    }

    /**
     * Displays the "Not Authorized" page.
     */
    public function notAuthorizedAction() {
        $this->getResponse()->setStatusCode(403);

        return new ViewModel();
    }

}
