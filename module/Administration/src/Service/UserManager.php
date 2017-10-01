<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Administration\Entity\User;
use Application\Model\IndexCache;

class UserManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;
    protected $userCache;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->userCache = new IndexCache();
    }

    public function findById($id) {
        if ($this->userCache->exists('id', $id)) {
            return $this->userCache->getElement('id', $id);
        }

        $user = $this->entityManager->getRepository(User::class)->find($id);
        if ($user) {
            $this->userCache->addElement($user, ['id' => $user->getId(), 'email' => $user->getEmail()]);
        }

        return $user;
    }

    public function findByEmail($email) {
        if ($this->userCache->exists('email', $email)) {
            return $this->userCache->getElement('email', $email);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            $this->userCache->addElement($user, ['id' => $user->getId(), 'email' => $user->getEmail()]);
        }

        return $user;
    }

    public function findByQuery(Query $userQuery) {
        return $userQuery->getResult();
    }

    public function save(User $user) {
        $this->userCache->deleteElement('id', $user->getId());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove($id) {
        $this->entityManager->remove($id);
    }

}
