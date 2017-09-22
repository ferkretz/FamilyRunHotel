<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Room;
use Application\Model\RoomQuery;

class RoomManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(Room::class)->find($id);
    }

    public function findByQuery(RoomQuery $query) {
        return $this->entityManager->getRepository(Room::class)->matching($query->getCriteria());
    }

    public function save(Room $room) {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
