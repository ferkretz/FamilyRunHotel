<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\Room;

class RoomManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(Room::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function add(Room $room) {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function update() {
        $this->entityManager->flush();
    }

    public function remove(Room $room) {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }

}
