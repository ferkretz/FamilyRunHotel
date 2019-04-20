<?php

namespace Application\Service\Room;

use Doctrine\ORM\EntityManager;
use Application\Entity\Room\Room;

class RoomManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Room {
        return $this->entityManager->getRepository(Room::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Room $room) {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function update(?Room $room = null) {
        $this->entityManager->flush($room);
    }

    public function remove(Room $room) {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
