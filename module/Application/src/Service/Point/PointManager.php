<?php

namespace Application\Service\Point;

use Doctrine\ORM\EntityManager;
use Application\Entity\Point\Point;

class PointManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Point {
        return $this->entityManager->getRepository(Point::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Point $point) {
        $this->entityManager->persist($point);
        $this->entityManager->flush();
    }

    public function update(?Point $point = null) {
        $this->entityManager->flush($point);
    }

    public function remove(Point $point) {
        $this->entityManager->remove($point);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
