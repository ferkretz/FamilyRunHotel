<?php

namespace Application\Service\Picture;

use Doctrine\ORM\EntityManager;
use Application\Entity\Picture\PictureEntity;
use Application\Service\AbstractQueryManager;

class PictureQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';

    protected $roomId;
    protected $InverseRoomId;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
        $this->roomId = 0;
        $this->inverseRoomId = FALSE;
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(PictureEntity::class)->createQueryBuilder('picture');
        if ($this->roomId > 0) {
            if ($this->inverseRoomId) {
                $queryBuilder->where(':roomId NOT MEMBER OF picture.rooms');
            } else {
                $queryBuilder->where(':roomId MEMBER OF picture.rooms');
            }
            $queryBuilder->setParameter('roomId', $this->roomId);
        }
        return $queryBuilder->getQuery();
    }

    public function getParams() {
        return ['orderBy' => $this->getOrderBy(), 'order' => $this->getOrder()];
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID]) ? $orderBy : self::ORDER_BY_ID;
    }

    public function setRoomId($roomId,
                              $inverseRoomId = FALSE) {
        $this->roomId = $roomId;
        $this->inverseRoomId = $inverseRoomId;
    }

}
