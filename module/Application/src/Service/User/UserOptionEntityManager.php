<?php

namespace Application\Service\User;

use Doctrine\ORM\EntityManager;
use Application\Entity\User\UserOptionEntity;
use Application\Service\AbstractEntityManager;

class UserOptionEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, UserOptionEntity::class);
    }

    public function findOneByName($name) {
        return $this->entityManager->getRepository(UserOptionEntity::class)->findOneBy([
                    'name' => $name
        ]);
    }

}
