<?php

namespace Application\Service\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Application\Entity\Service\ServiceEntity;
use Application\Service\AbstractQueryManager;

class ServiceQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_PRICE = 'price';
    const ORDER_BY_SUMMARY = 'translation.summary';

    protected $localeId;
    protected $minPrice;
    protected $maxPrice;
    protected $roomId;
    protected $InverseRoomId;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->localeId = 1;
        $this->minPrice = NULL;
        $this->maxPrice = NULL;
        $this->roomId = 0;
        $this->inverseRoomId = FALSE;
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(ServiceEntity::class)->createQueryBuilder('service');
        if ($this->roomId > 0) {
            if ($this->inverseRoomId) {
                $queryBuilder->where(':roomId NOT MEMBER OF service.rooms');
            } else {
                $queryBuilder->where(':roomId MEMBER OF service.rooms');
            }
            $queryBuilder->setParameter('roomId', $this->roomId);
        }
        if (($this->orderBy == self::ORDER_BY_SUMMARY) && ($this->localeId)) {
            $queryBuilder->addSelect('translation')
                    ->leftJoin('service.translations', 'translation', Join::WITH, 'translation.localeId = :localeId')
                    ->setParameter('localeId', $this->localeId);
        }
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

    public function getLocaleId() {
        return $this->localeId;
    }

    public function getMinPrice() {
        return $this->minPrice;
    }

    public function getMaxPrice() {
        return $this->maxPrice;
    }

    public function getParams() {
        return ['orderBy' => $this->getOrderBy(), 'order' => $this->getOrder(), 'localeId' => $this->getLocaleId()];
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
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_PRICE, self::ORDER_BY_SUMMARY]) ? $orderBy : self::ORDER_BY_ID;
    }

    public function setLocaleId($localeId,
                                $validLocaleIds = NULL) {
        if ($validLocaleIds) {
            $this->localeId = in_array($localeId, $validLocaleIds) ? $localeId : 1;
        } else {
            $this->localeId = $localeId;
        }
    }

    public function setRoomId($roomId,
                              $inverseRoomId = FALSE) {
        $this->roomId = $roomId;
        $this->inverseRoomId = $inverseRoomId;
    }

}
