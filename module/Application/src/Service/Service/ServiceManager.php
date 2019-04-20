<?php

namespace Application\Service\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Service\Service;

class ServiceManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Service {
        return $this->entityManager->getRepository(Service::class)->find($id);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Service $service) {
        $this->entityManager->persist($service);
        $this->entityManager->flush();
    }

    public function update(?Service $service = null) {
        $this->entityManager->flush($service);
    }

    public function remove(Service $service) {
        $this->entityManager->remove($service);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

}
