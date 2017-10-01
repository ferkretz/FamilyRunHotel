<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Administration\Entity\Option;

class OptionManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id,
                             $defaultValue = NULL) {
        $option = $this->entityManager->getRepository(Option::class)->find($id);
        return $option == NULL ? $defaultValue : $option->getValue();
    }

    public function findByName($name,
                               $defaultValue = NULL) {
        $option = $this->entityManager->getRepository(Option::class)->findOneBy(['name' => $name]);
        return $option == NULL ? $defaultValue : $option->getValue();
    }

    public function save(Option $option) {
        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
