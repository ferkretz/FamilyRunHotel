<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\User;

class UserManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function findByEmail($email) {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function add(User $user) {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update() {
        $this->entityManager->flush();
    }

    public function remove(User $user) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}
