<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Administration\Entity\RoomService;
use Administration\Model\RoomServiceQuery;

class RoomServiceManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(RoomService::class)->find($id);
    }

    public function findByQuery(RoomServiceQuery $query) {
        return $this->entityManager->getRepository(RoomService::class)->matching($query->getCriteria());
    }

    public function add(RoomService $roomService) {
        $this->entityManager->persist($roomService);
        $this->entityManager->flush();
    }

    public function update() {
        $this->entityManager->flush();
    }

    public function remove(RoomService $roomService) {
        $this->entityManager->remove($roomService);
        $this->entityManager->flush();
    }

}
