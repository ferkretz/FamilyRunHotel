<?php

namespace Application\Service\Room;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Application\Entity\Room\Room;
use Application\Service\Common\AbstractQueryManager;
use Application\Service\Option\SettingsManager;

class RoomQueryManager extends AbstractQueryManager {

    const PRICE = 1;
    const SUMMARY = 2;

    protected $settingsManager;
    protected $locale;
    protected $minPrice;
    protected $maxPrice;

    public function __construct(EntityManager $entityManager,
                                SettingsManager $settingsManager) {
        parent::__construct(
                $entityManager,
                ['price', 'translation.summary'],
                ['Price', 'Summary']
        );
        $this->settingsManager = $settingsManager;
        $this->locale = $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
        $this->minPrice = null;
        $this->maxPrice = null;
    }

    public function setLocale(?string $locale) {
        $this->locale = in_array($locale, $this->settingsManager->getSetting(SettingsManager::LOCALES)) ? $locale : $this->settingsManager->getSetting(SettingsManager::LOCALE, true);
    }

    public function setMinPrice(?float $minPrice) {
        $this->minPrice = $minPrice;
    }

    public function setMaxPrice(?float $maxPrice) {
        $this->maxPrice = $maxPrice;
    }

    public function getLocale(): ?string {
        return $this->locale;
    }

    public function getMinPrice(): ?float {
        return $this->minPrice;
    }

    public function getMaxPrice(): ?float {
        return $this->maxPrice;
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

        if (($this->minPrice) && ($this->maxPrice)) {
            if ($this->minPrice > $this->maxPrice) {
                list($this->minPrice, $this->maxPrice) = [$this->maxPrice, $this->minPrice];
            }
        }
        if ($this->minPrice) {
            $criteria->andWhere(Criteria::expr()->gte('price', $this->minPrice));
        }
        if ($this->maxPrice) {
            $criteria->andWhere(Criteria::expr()->lte('price', $this->maxPrice));
        }

        return $criteria;
    }

    public function getQuery(): Query {
        $queryBuilder = $this->entityManager->getRepository(Room::class)->createQueryBuilder('room');
        if (($this->orderBy == $this->orderByList[self::SUMMARY]) && ($this->locale)) {
            $queryBuilder->addSelect('translation')
                    ->leftJoin('room.translations', 'translation', Join::WITH, 'translation.locale = :locale')
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
