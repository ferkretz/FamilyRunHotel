<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\User;
use Application\Service\AbstractQueryManager;

class UserQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_REAL_NAME = 'realName';
    const ORDER_BY_EMAIL = 'email';
    const ORDER_BY_ROLE = 'role';

    protected $role;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->role = NULL;
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(User::class)->createQueryBuilder('user');
        $queryBuilder->addCriteria($this->getCriteria());
        return $queryBuilder->getQuery();
    }

    public function getCriteria() {
        $criteria = parent::getCriteria();

        if ($this->role) {
            $criteria->andWhere(Criteria::expr()->eq('role', $this->role));
        }

        return $criteria;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_REAL_NAME, self::ORDER_BY_EMAIL, self::ORDER_BY_ROLE]) ? $orderBy : NULL;
    }

}
