<?php

namespace Application\Service\User;

use Doctrine\ORM\EntityManager;
use Application\Entity\User\User;

class UserManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?User {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function findOneByEmail(string $email): ?User {
        return $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $email
        ]);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function insert(User $user) {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(?User $user = null) {
        $this->entityManager->flush($user);
    }

    public function remove(User $user) {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function removeById(int $id) {
        $this->remove($this->findOneById($id));
    }

    public function removeByEmail(string $email) {
        $this->remove($this->findOneByEmail($email));
    }

}
