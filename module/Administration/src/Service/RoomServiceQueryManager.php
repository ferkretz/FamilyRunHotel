<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\RoomService;
use Application\Service\AbstractQueryManager;

class RoomServiceQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_PRICE = 'price';
    const ORDER_BY_SUMMARY = 'summary';
    const ORDER_BY_TRANS_COUNT = 'transCount';

    protected $minPrice;
    protected $maxPrice;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->minPrice = NULL;
        $this->maxPrice = NULL;
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(RoomService::class)->createQueryBuilder('service');
        $queryBuilder->addCriteria($this->getCriteria());
        return $queryBuilder->getQuery();
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
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_PRICE, self::ORDER_BY_SUMMARY, self::ORDER_BY_TRANS_COUNT]) ? $orderBy : NULL;
    }

}
