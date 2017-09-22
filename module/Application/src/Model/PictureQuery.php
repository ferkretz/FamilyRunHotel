<?php

namespace Application\Model;

use Doctrine\Common\Collections\Criteria;

class RoomQuery extends AbstractQuery {

    const ORDER_BY_ID = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function getCriteria() {
        $criteria = parent::getCriteria();

        return $criteria;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID]) ? $orderBy : NULL;
    }

}
