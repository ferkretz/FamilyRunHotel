<?php

namespace Application\Service\User;

use Doctrine\ORM\EntityManager;
use Application\Entity\User\UserEntity;
use Application\Service\AbstractEntityManager;

class UserEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, UserEntity::class);
    }

    public function findOneByEmail($email) {
        return $this->entityManager->getRepository(UserEntity::class)->findOneBy([
                    'email' => $email
        ]);
    }

}
