<?php

namespace Application\Service\Reservation;

use Doctrine\ORM\EntityManager;
use Application\Entity\Reservation\ReservationEntity;
use Application\Service\AbstractEntityManager;

class ReservationEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, ReservationEntity::class);
    }

    public function findOneByComposite(int $userId,
                                       int $roomId,
                                       \DateTime $month) {
        return $this->entityManager->getRepository(ReservationEntity::class)->findOneBy([
                    'userId' => $userId,
                    'roomId' => $roomId,
                    'month' => $month,
        ]);
    }

}
