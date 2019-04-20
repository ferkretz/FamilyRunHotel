<?php

namespace Application\Service\Photo;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\Photo\Photo;
use Application\Service\Common\AbstractQueryManager;

class PhotoQueryManager extends AbstractQueryManager {

    protected $inverseRoomSelect;
    protected $roomId;

    public function __construct(EntityManager $entityManager) {
        parent::__construct(
                $entityManager
        );
        $this->inverseRoomSelect = false;
        $this->roomId = null;
    }

    public function setInverseRoomSelect(bool $inversRoomSelect) {
        $this->inverseRoomSelect = $inversRoomSelect;
    }

    public function setRoomId(?int $roomId) {
        $this->roomId = $roomId;
    }

    public function getInverseRoomSelect(): bool {
        return $this->inverseRoomSelect;
    }

    public function getRoomId(): ?int {
        return $this->roomId;
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
        $queryBuilder = $this->entityManager->getRepository(Photo::class)->createQueryBuilder('photo');
        if (($this->roomId ? $this->roomId : 0) > 0) {
            if ($this->inverseRoomSelect) {
                $queryBuilder->where(':roomId NOT MEMBER OF photo.rooms');
            } else {
                $queryBuilder->where(':roomId MEMBER OF photo.rooms');
            }
            $queryBuilder->setParameter('roomId', $this->roomId);
        }

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

            return $params;
        } else {
            return ['orderBy' => $this->orderBy, 'order' => $this->order];
        }
    }

}
