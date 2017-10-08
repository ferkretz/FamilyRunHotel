<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Entity\User;
use Administration\Form\UserAddForm;
use Administration\Form\UserEditForm;
use Administration\Form\UserIndexForm;
use Administration\Service\UserQueryManager;
use Administration\Service\UserManager;

class UserController extends AbstractActionController {

    /**
     * UserQuery manager.
     * @var UserQueryManager
     */
    protected $userQueryManager;

    /**
     * User manager.
     * @var UserManager
     */
    protected $userManager;

    /**
     * Roles from the 'capability_config' config key.
     * @var array
     */
    protected $roles;

    public function __construct(UserQueryManager $userQueryManager,
                                UserManager $userManager,
                                $roles) {
        $this->userQueryManager = $userQueryManager;
        $this->userManager = $userManager;
        $this->roles = $roles;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $orderBy = $this->params()->fromQuery('orderBy', UserQueryManager::ORDER_BY_ID);
        $order = $this->params()->fromQuery('order', UserQueryManager::ORDER_ASC);

        $this->userQueryManager->setOrder($order);
        $this->userQueryManager->setOrderBy($orderBy);
        $userQuery = $this->userQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($userQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        foreach ($paginator as $user) {
            $userIds[] = $user->getId();
        }
        $form = new UserIndexForm($userIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (count($data)) {
                    foreach ($data['users'] as $index) {
                        $users[] = $paginator->getItem($index + 1);
                    }
                    foreach ($users as $user) {
                        $this->userManager->remove($user);
                    }
                } else {
                    throw new \Exception('There are no users to delete.');
                }

                return $this->redirect()->toRoute('admin-users');
            } else {
                throw new \Exception(current($form->getMessages()['users'][0]));
            }
        }

        return new ViewModel([
            'form' => $form,
            'users' => $paginator,
            'orderBy' => $this->userQueryManager->getOrderBy(),
            'order' => $this->userQueryManager->getOrder(),
            'requiredQuery' => ['orderBy' => $this->userQueryManager->getOrderBy(), 'order' => $this->userQueryManager->getOrder()],
        ]);
    }

    public function editAction() {
        $form = new UserEditForm($this->roles);

        $id = $this->params()->fromRoute('id', -1);
        $user = $this->userManager->findById($id);
        if ($user == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (empty($data['password'])) {
                    unset($data['password']);
                }
                $user->setData($data);
                $this->userManager->update();

                return $this->redirect()->toRoute('admin-users');
            }
        } else {
            $data = $user->getData();
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function addAction() {
        $form = new UserAddForm($this->roles);

        $user = new User();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (empty($data['password'])) {
                    unset($data['password']);
                }
                $user->setData($data);
                $user->setRegistered(new \DateTime(NULL, new \DateTimeZone("UTC")));
                $this->userManager->add($user);

                return $this->redirect()->toRoute('admin-users');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
