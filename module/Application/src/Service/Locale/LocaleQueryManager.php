<?php

namespace Application\Service\Locale;

use Doctrine\ORM\EntityManager;
use Application\Entity\Locale\LocaleEntity;
use Application\Service\AbstractQueryManager;

class LocaleQueryManager extends AbstractQueryManager {

    const ORDER_BY_ID = 'id';
    const ORDER_BY_NAME = 'name';

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager);
    }

    public function getQuery() {
        $queryBuilder = $this->entityManager->getRepository(LocaleEntity::class)->createQueryBuilder('locale');
        $queryBuilder->addCriteria($this->getCriteria());

        return $queryBuilder->getQuery();
    }

    public function getParams() {
        return ['orderBy' => $this->getOrderBy(), 'order' => $this->getOrder()];
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = in_array($orderBy, [self::ORDER_BY_ID, self::ORDER_BY_NAME]) ? $orderBy : self::ORDER_BY_ID;
    }

}
