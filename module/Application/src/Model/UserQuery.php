<?php

namespace Application\Model;

use Doctrine\Common\Collections\Criteria;

class UserQuery extends AbstractQuery {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_LOGIN = 'login';
    const ORDER_BY_ROLE = 'role';

    protected $role;

    public function __construct() {
        parent::__construct();
        $this->role = NULL;
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
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_LOGIN, self::ORDER_BY_ROLE]) ? $orderBy : NULL;
    }

}
