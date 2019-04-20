<?php

namespace Application\Service\Option;

use Doctrine\ORM\EntityManager;
use Application\Entity\Option\Option;

class OptionManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Option {
        return $this->entityManager->getRepository(Option::class)->find($id);
    }

    public function findOneByName(string $name,
                                  ?int $userId = null): ?Option {
        return $this->entityManager->getRepository(Option::class)->findOneBy([
                    'name' => $name,
                    'userId' => $userId ?? 0,
        ]);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(Option $option) {
        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }

    public function update(?Option $option = null) {
        $this->entityManager->flush($option);
    }

    public function remove(Option $option) {
        $this->entityManager->remove($option);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

    public function removeByName(string $name) {
        $this->remove($this->findOneByEmail($name));
    }

}
