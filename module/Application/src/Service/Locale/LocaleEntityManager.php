<?php

namespace Application\Service\Locale;

use Doctrine\ORM\EntityManager;
use Application\Entity\Locale\LocaleEntity;
use Application\Service\AbstractEntityManager;

class LocaleEntityManager extends AbstractEntityManager {

    private $current;

    public function __construct(EntityManager $entityManager) {
        parent::__construct($entityManager, LocaleEntity::class);
    }

    public function getCurrent() {
        return $this->current;
    }

    public function setCurrent($localeEntity) {
        $this->current = $localeEntity;
    }

    public function setCurrentByName($name) {
        $this->current = $this->findOneByName($name);
    }

    public function findOneByName(string $name) {
        return $this->entityManager->getRepository(LocaleEntity::class)->findOneBy([
                    'name' => $name,
        ]);
    }

    public function findAllId() {
        $queryBuilder = $this->entityManager->getRepository(LocaleEntity::class)->createQueryBuilder('locale');
        $queryBuilder->select('locale.id')->orderBy('locale.id');
        return $queryBuilder->getQuery()->getResult("ColumnHydrator");
    }

    public function findAllName($skipFirst = FALSE) {
        $localeEntities = $this->entityManager->getRepository(LocaleEntity::class)->findAll();

        $names = [];

        foreach ($localeEntities as $localeEntity) {
            if ($skipFirst && $localeEntity->getId() == 1) {
                continue;
            }
            $names[$localeEntity->getId()] = $localeEntity->getName();
        }

        return $names;
    }

    public function findAllDisplayName($skipFirst = FALSE) {
        $localeEntities = $this->entityManager->getRepository(LocaleEntity::class)->findAll();

        $names = [];

        foreach ($localeEntities as $localeEntity) {
            if ($skipFirst && $localeEntity->getId() == 1) {
                continue;
            }
            $names[$localeEntity->getId()] = \Locale::getDisplayName($localeEntity->getName());
        }

        return $names;
    }

}
