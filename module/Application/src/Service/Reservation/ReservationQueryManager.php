<?php

namespace Application\Service\Reservation;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Application\Entity\Reservation\ReservationEntity;
use Application\Service\AbstractQueryManager;

class ReservationQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_MONTH = 'month';
    const ORDER_BY_ROOM = 'room';

    protected $userId;
    protected $localeId;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->userId = 0;
        $this->localeId = 1;
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(ReservationEntity::class)->createQueryBuilder('reservation');
        if ($this->localeId > 0) {
            $queryBuilder->addSelect('room')->leftJoin('reservation.room', 'room');
            $queryBuilder->where(':localeId MEMBER OF room.translations')
                    ->setParameter('localeId', $this->localeId);
        }
        $queryBuilder->addCriteria($this->getCriteria());

        return $queryBuilder->getQuery();
    }

    public function getCriteria() {
        $criteria = parent::getCriteria();

        if ($this->userId) {
            $criteria->andWhere(Criteria::expr()->eq('userId', $this->userId));
        }

        return $criteria;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getLocaleId() {
        return $this->userId;
    }

    public function getParams() {
        return ['orderBy' => $this->getOrderBy(), 'order' => $this->getOrder(), 'userId' => $this->getUserId()];
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_MONTH, self::ORDER_BY_ROOM]) ? $orderBy : self::ORDER_BY_ID;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setLocaleId($localeId) {
        $this->localeId = $localeId;
    }

}
