<?php

namespace Application\Service\User;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\User\User;
use Application\Service\Common\AbstractQueryManager;

class UserQueryManager extends AbstractQueryManager {

    const REAL_NAME = 1;
    const EMAIL = 2;
    const ROLE = 3;

    protected $role;

    public function __construct(EntityManager $entityManager) {
        parent::__construct(
                $entityManager,
                ['realName', 'email', 'role'],
                ['Name', 'Email', 'Role']
        );
        $this->role = null;
    }

    public function setRole(?string $role) {
        $this->role = $role;
    }

    public function getRole(): ?string {
        return $this->role;
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

        if ($this->role) {
            $criteria->andWhere(Criteria::expr()->eq('role', $this->role));
        }

        return $criteria;
    }

    public function getQuery(): Query {
        $queryBuilder = $this->entityManager->getRepository(User::class)->createQueryBuilder('user');
        $queryBuilder->addCriteria($this->getCriteria());

        return $queryBuilder->getQuery();
    }

    public function getParams(?array $overrides = NULL): array {
        if ($overrides) {
            $params = $this->getParams();

            switch ($overrides['orderBy']) {
                case '%DEFAULT%':
                    $params['orderBy'] = $this->orderByList[self::ID];
                    break;
                default:
                    $params['orderBy'] = in_array($overrides['orderBy'], $this->orderByList) ? $overrides['orderBy'] : $this->orderByList[self::ID];
            }

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

            return $params;
        } else {
            return ['orderBy' => $this->orderBy, 'order' => $this->order];
        }
    }

}
