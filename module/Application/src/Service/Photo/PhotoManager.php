<?php

namespace Application\Service\Photo;

use Doctrine\ORM\EntityManager;
use Application\Entity\Photo\Photo;

class PhotoManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Photo {
        return $this->entityManager->getRepository(Photo::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Photo $photo) {
        $this->entityManager->persist($photo);
        $this->entityManager->flush();
    }

    public function update(?Photo $photo = null) {
        $this->entityManager->flush($photo);
    }

    public function remove(Photo $photo) {
        $this->entityManager->remove($photo);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
