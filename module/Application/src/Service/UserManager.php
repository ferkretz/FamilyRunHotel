<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\User;
use Application\Model\UserQuery;

class UserManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findById($id) {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    /**
     * 
     * @param type $login
     * @return User
     */
    public function findByLogin($login) {
        return $this->entityManager->getRepository(User::class)->findOneBy(['login' => $login]);
    }

    public function findByEmail($email) {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function findByQuery(UserQuery $query) {
        return $this->entityManager->getRepository(User::class)->matching($query->getCriteria());
    }

    public function save(User $user) {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
