<?php

namespace Application\Service\Site;

use Doctrine\ORM\EntityManager;
use Application\Entity\Site\SiteOptionEntity;
use Application\Service\AbstractEntityManager;

class SiteOptionEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, SiteOptionEntity::class);
    }

    public function findOneByName(string $name) {
        return $this->entityManager->getRepository(SiteOptionEntity::class)->findOneBy([
                    'name' => $name
        ]);
    }

}
