<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
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

    public function __construct(UserQueryManager $userQueryManager,
                                UserManager $userManager) {
        $this->userQueryManager = $userQueryManager;
        $this->userManager = $userManager;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $orderBy = $this->params()->fromQuery('orderBy', UserQueryManager::ORDER_BY_ID);
        $order = $this->params()->fromQuery('order', UserQueryManager::ORDER_ASC);

        $this->userQueryManager->setOrder($order);
        $this->userQueryManager->setOrderBy($orderBy);
        $userQuery = $this->userQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($userQuery, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'users' => $paginator,
            'orderBy' => $this->userQueryManager->getOrderBy(),
            'order' => $this->userQueryManager->getOrder(),
            'requiredQuery' => ['orderBy' => $this->userQueryManager->getOrderBy(), 'order' => $this->userQueryManager->getOrder()],
        ]);
    }

    public function editAction() {

    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
