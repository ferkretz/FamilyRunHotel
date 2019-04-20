<?php

namespace Application\Service\Point;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Application\Entity\Point\Point;
use Application\Service\Common\AbstractQueryManager;
use Application\Service\Option\SettingsManager;

class PointQueryManager extends AbstractQueryManager {

    const ICON = 1;
    const SUMMARY = 2;

    protected $settingsManager;
    protected $locale;

    public function __construct(EntityManager $entityManager,
                                SettingsManager $settingsManager) {
        parent::__construct(
                $entityManager,
                ['icon', 'translation.summary'],
                ['Icon', 'Summary']
        );
        $this->settingsManager = $settingsManager;
        $this->locale = $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
    }

    public function setLocale(?string $locale) {
        $this->locale = in_array($locale, $this->settingsManager->getSetting(SettingsManager::LOCALES)) ? $locale : $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
    }

    public function getLocale(): ?string {
        return $this->locale;
    }

    public function getCriteria(): Criteria {
        $criteria = new Criteria();

        if ($this->orderBy) {
            $criteria->orderBy([$this->orderBy => $this->order]);
        }

        if ($this->firstResult) {
            $criteria->setFirstResult($this->firstResult);
        }
        if ($this->maxResults) {
            $criteria->setMaxResults($this->maxResults);
        }

        return $criteria;
    }

    public function getQuery(): Query {
        $queryBuilder = $this->entityManager->getRepository(Point::class)->createQueryBuilder('point');
        if (($this->orderBy == $this->orderByList[self::SUMMARY]) && ($this->locale)) {
            $queryBuilder->addSelect('translation')
                    ->leftJoin('point.translations', 'translation', Join::WITH, 'translation.locale = :locale')
                    ->setParameter('locale', $this->locale);
        }
        $queryBuilder->addCriteria($this->getCriteria());

        return $queryBuilder->getQuery();
    }

    public function getParams(?array $overrides = NULL): array {
        if ($overrides) {
            $params = $this->getParams();

            if (!empty($overrides['orderBy'])) {
                switch ($overrides['orderBy']) {
                    case '%DEFAULT%':
                        $params['orderBy'] = $this->orderByList[self::ID];
                        break;
                    default:
                        $params['orderBy'] = in_array($overrides['orderBy'], $this->orderByList) ? $overrides['orderBy'] : $this->orderByList[self::ID];
                }
            }

            if (!empty($overrides['order'])) {
                switch ($overrides['order']) {
                    case '%DEFAULT%':
                        $params['order'] = $this->orderList[self::ASC];
                        break;
                    case '%INVERSE%':
                        $params['order'] = $this->order == $this->orderList[self::ASC] ? $this->orderList[self::DESC] : $this->orderList[self::ASC];
                        break;
                    default:
                        $params['order'] = in_array($overrides['order'], $this->orderList) ? $overrides['order'] : $this->orderList[self::ASC];
                }
            }

            if (!empty($overrides['locale'])) {
                switch ($overrides['locale']) {
                    case '%DEFAULT%':
                        $params['locale'] = $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
                        break;
                    default:
                        $params['locale'] = in_array($overrides['locale'], $this->settingsManager->getSetting(SettingsManager::LOCALES)) ? $overrides['locale'] : $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
                }
            }

            return $params;
        } else {
            return ['orderBy' => $this->orderBy, 'order' => $this->order, 'locale' => $this->locale];
        }
    }

}
