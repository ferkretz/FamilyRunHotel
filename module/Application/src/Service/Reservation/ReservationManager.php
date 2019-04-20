<?php

namespace Application\Service\Reservation;

use Doctrine\ORM\EntityManager;
use Application\Entity\Reservation\Reservation;

class ReservationManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id) {
        return $this->entityManager->getRepository(Reservation::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Reservation $reservation) {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    public function update(Reservation $reservation = null) {
        $this->entityManager->flush($reservation);
    }

    public function remove(Reservation $reservation) {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
