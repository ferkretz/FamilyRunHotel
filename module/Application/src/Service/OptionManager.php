<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Option;

class OptionManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id,
                             $defaultValue = NULL) {
        $value = $this->entityManager->getRepository(Option::class)->find($id);
        return $value == NULL ? $defaultValue : $value;
    }

    public function findByName($name,
                               $defaultValue = NULL) {
        $value = $this->entityManager->getRepository(Option::class)->findOneBy(['name' => $name]);
        return $value == NULL ? $defaultValue : $value;
    }

    public function save(Option $option) {
        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
