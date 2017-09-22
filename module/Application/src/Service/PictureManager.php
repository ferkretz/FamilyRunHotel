<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Picture;
use Application\Model\PictureQuery;

class PictureManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(Picture::class)->find($id);
    }

    public function findByQuery(PictureQuery $query) {
        return $this->entityManager->getRepository(Picture::class)->matching($query->getCriteria());
    }

    public function save(Picture $picture) {
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
