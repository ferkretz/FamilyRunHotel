<?php

namespace Application\Service\Room;

use Doctrine\ORM\EntityManager;
use Application\Entity\Room\RoomEntity;
use Application\Service\AbstractEntityManager;

class RoomEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, RoomEntity::class);
    }

}
