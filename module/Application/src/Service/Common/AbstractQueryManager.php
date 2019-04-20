<?php

namespace Application\Service\Common;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

abstract class AbstractQueryManager {

    const ASC = 0;
    const DESC = 1;
    const ID = 0;

    protected $orderByList;
    protected $orderList;
    protected $columnList;
    protected $entityManager;
    protected $orderBy;
    protected $order;
    protected $firstResult;
    protected $maxResults;

    public function __construct(EntityManager $entityManager,
                                ?array $orderByList = null,
                                ?array $columnList = null) {
        $this->orderByList = array_merge(['id'], $orderByList ?? []);
        $this->orderList = [Criteria::ASC, Criteria::DESC];
        $this->columnList = array_merge(['#'], $columnList ?? []);
        $this->entityManager = $entityManager;
        $this->orderBy = $this->orderByList[self::ID];
        $this->order = $this->orderList[self::ASC];
        $this->firstResult = null;
        $this->maxResult = null;
    }

    public function setOrderBy(?string $orderBy) {
        $this->orderBy = in_array($orderBy, $this->orderByList) ? $orderBy : $this->orderByList[self::ID];
    }

    public function setOrder(?string $order) {
        $this->order = in_array($order, $this->orderList) ? $order : $this->orderList[self::ASC];
    }

    public function setFirstResult(?int $firstResult) {
        $this->firstResult = $firstResult;
    }

    public function setMaxResults(?int $maxResults) {
        $this->maxResults = $maxResults;
    }

    public function getOrderList(): array {
        return $this->orderList;
    }

    public function getOrderByList(): array {
        return $this->orderByList;
    }

    public function getColumnIdList(): array {
        return $this->orderByList;
    }

    public function getColumnNameList(): array {
        return $this->columnList;
    }

    abstract public function getCriteria(): Criteria;

    abstract public function getQuery(): Query;

    abstract public function getParams(?array $overrides): array;

    public function getOrderBy(): ?string {
        return $this->orderBy;
    }

    public function getOrder(): ?string {
        return $this->order;
    }

    public function getInverseOrder(): ?string {
        return $this->order == $this->orderList[self::ASC] ? $this->orderList[self::DESC] : $this->orderList[self::ASC];
    }

    public function getFirstResult(): ?int {
        return $this->firstResult;
    }

    public function getMaxResults(): ?int {
        return $this->maxResults;
    }

}
