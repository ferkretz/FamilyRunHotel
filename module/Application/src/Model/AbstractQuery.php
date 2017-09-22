<?php

namespace Application\Model;

use Doctrine\Common\Collections\Criteria;

class AbstractQuery {

    const ORDER_ASC = Criteria::ASC;
    const ORDER_DESC = Criteria::DESC;

    protected $orderBy;
    protected $order;
    protected $firstResult;
    protected $maxResults;

    public function __construct() {
        $this->orderBy = NULL;
        $this->order = self::ORDER_ASC;
        $this->firstResult = NULL;
        $this->maxResult = NULL;
    }

    public function getCriteria() {
        $criteria = new Criteria();

        if ($this->orderBy) {
            $criteria->orderBy([$this->orderBy => $this->order]);
        }

        if ($this->firstResult) {
            $criteria->setFirstResult($this->firstResult);
        }
        if ($this->maxResults) {
            $criteria->setMaxResults($this->maxResults);
        }

        return $criteria;
    }

    public function getOrderBy() {
        return $this->orderBy;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getFirstResult() {
        return $this->firstResult;
    }

    public function getMaxResults() {
        return $this->maxResults;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    public function setOrder($order) {
        $this->order = in_array($order, [self::ORDER_ASC, self::ORDER_DESC]) ? $order : self::ORDER_ASC;
    }

    public function setFirstResult($firstResult) {
        $this->firstResult = $firstResult < 0 ? 0 : $firstResult;
    }

    public function setMaxResults($maxResults) {
        $this->maxResults = $maxResults < 0 ? 0 : $maxResults;
    }

}