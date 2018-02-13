<?php

namespace Application\Service\Picture;

use Doctrine\ORM\EntityManager;
use Application\Entity\Picture\PictureEntity;
use Application\Service\AbstractEntityManager;

class PictureEntityManager extends AbstractEntityManager {

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, PictureEntity::class);
    }

}
