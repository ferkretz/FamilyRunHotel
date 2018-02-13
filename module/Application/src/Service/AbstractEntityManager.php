<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

abstract class AbstractEntityManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager,
                                string $entityName) {
        $this->entityManager = $entityManager;
        $this->entityName = $entityName;
    }

    public function findOneById(int $id) {
        return $this->entityManager->getRepository($this->entityName)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert($entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function update($entity = NULL) {
        $this->entityManager->flush($entity);
    }

    public function remove($entity) {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
