<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\Picture;

class PictureManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(Picture::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function add(Picture $picture) {
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
    }

    public function update() {
        $this->entityManager->flush();
    }

    public function remove(Picture $picture) {
        $this->entityManager->remove($picture);
        $this->entityManager->flush();
    }

}
