<?php

namespace Application\Model;

use Doctrine\Common\Collections\Criteria;

class RoomServiceQuery extends AbstractQuery {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_PRICE = 'price';

    protected $minPrice;
    protected $maxPrice;

    public function __construct() {
        parent::__construct();
        $this->minPrice = NULL;
        $this->maxPrice = NULL;
    }

    public function getCriteria() {
        $criteria = parent::getCriteria();

        if ($this->minPrice) {
            $criteria->andWhere(Criteria::expr()->gte('price', $this->minPrice));
        }
        if ($this->maxPrice) {
            $criteria->andWhere(Criteria::expr()->lte('price', $this->maxPrice));
        }

        return $criteria;
    }

    public function getMinPrice() {
        return $this->minPrice;
    }

    public function getMaxPrice() {
        return $this->maxPrice;
    }

    public function setPrices($minPrice,
                              $maxPrice) {
        $this->minPrice = $minPrice < 0 ? 0 : $minPrice;
        $this->maxPrice = $maxPrice < 0 ? 0 : $maxPrice;

        if ($this->minPrice > $this->maxPrice) {
            list($this->minPrice, $this->maxPrice) = [$this->maxPrice, $this->minPrice];
        }
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_PRICE]) ? $orderBy : NULL;
    }

}
