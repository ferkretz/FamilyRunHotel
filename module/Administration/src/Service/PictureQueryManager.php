<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Application\Entity\Picture;
use Application\Service\AbstractQueryManager;

class PictureQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(Picture::class)->createQueryBuilder('picture');
        $queryBuilder->addCriteria($this->getCriteria());
        return $queryBuilder->getQuery();
    }

    public function getCriteria() {
        $criteria = parent::getCriteria();

        return $criteria;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID]) ? $orderBy : NULL;
    }

}
