<?php

namespace Application\Service\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Service\ServiceEntity;
use Application\Service\AbstractEntityManager;

class ServiceEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, ServiceEntity::class);
    }

}
