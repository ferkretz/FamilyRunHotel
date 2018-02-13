<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\User\AddForm;
use Administration\Form\User\EditForm;
use Administration\Form\User\IndexForm;
use Application\Entity\User\UserEntity;
use Application\Service\User\UserEntityManager;
use Application\Service\User\UserQueryManager;

class UserController extends AbstractActionController {

    /**
     * User entity manager.
     * @var UserEntityManager
     */
    protected $userEntityManager;

    /**
     * Roles from the 'roles' config key.
     * @var array
     */
    protected $roles;

    public function __construct(UserEntityManager $userEntityManager,
                                array $roles) {
        $this->userEntityManager = $userEntityManager;
        $this->roles = $roles;
    }

    public function indexAction() {
        $userQueryManager = $this->queryManager(UserQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $userQueryManager->setOrder($this->params()->fromQuery('order'));
        $userQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $userQuery = $userQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($userQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $userIds = [];
        foreach ($paginator as $userEntity) {
            $userIds[$userEntity->getId()] = $userEntity->getId();
        }
        $form = new IndexForm($userIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $protectException = FALSE;

                    foreach ($data['users'] as $userId) {
                        if ($userId == 1) {
                            $protectException = TRUE;
                        } else {
                            $this->userEntityManager->removeById($userId);
                        }
                    }

                    if ($protectException) {
                        throw new \Exception('Default user can not be deleted.');
                    }
                } else {
                    throw new \Exception('There are no users to delete.');
                }

                return $this->redirect()->toRoute(NULL);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationUser';

        return new ViewModel([
            'form' => $form,
            'users' => $paginator,
            'userQueryManager' => $userQueryManager,
        ]);
    }

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $userEntity = $this->userEntityManager->findOneById($id);

        if ($userEntity == NULL) {
            return $this->notFoundAction();
        }

        $form = new EditForm($this->roles);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $userEntity->setEmail($data['email']);
                $userEntity->setRealName($data['realName']);
                $userEntity->setDisplayName(empty($data['displayName']) ? NULL : $data['displayName']);
                $userEntity->setAddress($data['address']);
                $userEntity->setPhone($data['phone']);
                $userEntity->setRole($data['role']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $userEntity->setPassword($bcrypt->create($data['password']));
                }
                $this->userEntityManager->update();
            }
        } else {
            $data['email'] = $userEntity->getEmail();
            $data['realName'] = $userEntity->getRealName();
            $data['diaplayName'] = $userEntity->getDisplayName();
            $data['address'] = $userEntity->getAddress();
            $data['phone'] = $userEntity->getPhone();
            $data['role'] = $userEntity->getRole();
            $form->setData($data);
        }

        $this->layout()->activeMenuItemId = 'administrationUser';

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function addAction() {
        $form = new AddForm($this->roles);

        $userEntity = new UserEntity();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $userEntity->setEmail($data['email']);
                $userEntity->setRealName($data['realName']);
                $userEntity->setDisplayName(empty($data['displayName']) ? NULL : $data['displayName']);
                $userEntity->setAddress($data['address']);
                $userEntity->setPhone($data['phone']);
                $userEntity->setRole($data['role']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $userEntity->setPassword($bcrypt->create($data['password']));
                }
                $userEntity->setRegistered(new \DateTime(NULL, new \DateTimeZone("UTC")));
                $this->userEntityManager->insert($userEntity);

                return $this->redirect()->toRoute(NULL, ['action' => 'edit', 'id' => $userEntity->getId()]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationUser';

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
