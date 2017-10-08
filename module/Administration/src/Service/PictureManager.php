<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Administration\Entity\Picture;
use Administration\Model\PictureQuery;

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

    public function findByQuery(PictureQuery $query) {
        return $this->entityManager->getRepository(Picture::class)->matching($query->getCriteria());
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
