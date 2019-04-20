<?php

namespace Application\Controller\Administration;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\Query;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Entity\User\User;
use Application\Form\Administration\User\AddForm;
use Application\Form\Administration\User\EditForm;
use Application\Form\Administration\User\ListForm;
use Application\Service\Option\SettingsManager;
use Application\Service\User\AccessManager;
use Application\Service\User\UserManager;
use Application\Service\User\UserQueryManager;

class UserController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * User access manager.
     * @var AccessManager
     */
    protected $accessManager;

    /**
     * User entity manager.
     * @var UserManager
     */
    protected $userManager;

    /**
     * User query manager.
     * @var UserQueryManager
     */
    protected $userQueryManager;

    public function __construct(SettingsManager $settingsManager,
                                AccessManager $accessManager,
                                UserManager $userManager,
                                UserQueryManager $userQueryManager) {
        $this->settingsManager = $settingsManager;
        $this->accessManager = $accessManager;
        $this->userManager = $userManager;
        $this->userQueryManager = $userQueryManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('list.users');

        $page = $this->params()->fromQuery('page', 1);

        $this->userQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->userQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $userQuery = $this->userQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($userQuery, false));
        $users = new Paginator($adapter);
        $users->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $users->setCurrentPageNumber($page);

        $userIds = [];
        foreach ($users as $user) {
            $userIds[$user->getId()] = $user->getId();
        }
        $form = new ListForm($userIds);

        if ($this->getRequest()->isPost()) {
            $this->accessManager->currentUserTry('delete.users');

            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $protectException = false;

                    foreach ($data['users'] as $userId) {
                        if ($userId == 1) {
                            $protectException = true;
                        } else {
                            $this->userManager->removeById($userId);
                        }
                    }

                    if ($protectException) {
                        throw new \Exception('Default user can not be deleted.');
                    }
                } else {
                    throw new \Exception('There are no users to delete.');
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'users' => $users,
            'userQueryManager' => $this->userQueryManager,
            'accessManager' => $this->accessManager
        ]);
    }

    public function editAction() {
        $this->accessManager->currentUserRedirect('edit.users');

        $id = $this->params()->fromRoute('id', -1);
        $user = $this->userManager->findOneById($id);
        if (!$user) {
            return $this->notFoundAction();
        }

        $form = new EditForm($this->accessManager->getRoleList());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $user->setEmail($data['email']);
                $user->setRealName($data['realName']);
                $user->setDisplayName(empty($data['displayName']) ? null : $data['displayName']);
                $user->setAddress($data['address']);
                $user->setPhone($data['phone']);
                $user->setRole($data['role']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $user->setPassword($bcrypt->create($data['password']));
                }
                $this->userManager->update();
            }
        } else {
            $data['email'] = $user->getEmail();
            $data['realName'] = $user->getRealName();
            $data['diaplayName'] = $user->getDisplayName();
            $data['address'] = $user->getAddress();
            $data['phone'] = $user->getPhone();
            $data['role'] = $user->getRole();
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function addAction() {
        $this->accessManager->currentUserRedirect('add.users');

        $form = new AddForm($this->accessManager->getRoleList());

        $user = new User();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $user->setEmail($data['email']);
                $user->setRealName($data['realName']);
                $user->setDisplayName(empty($data['displayName']) ? null : $data['displayName']);
                $user->setAddress($data['address']);
                $user->setPhone($data['phone']);
                $user->setRole($data['role']);
                if (!empty($data['password'])) {
                    $bcrypt = new Bcrypt();
                    $user->setPassword($bcrypt->create($data['password']));
                }
                $user->setRegistered(new \DateTime(null, new \DateTimeZone("UTC")));
                $this->userManager->insert($user);

                return $this->redirect()->toRoute(null, ['action' => 'edit', 'id' => $user->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
