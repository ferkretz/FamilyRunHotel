<?php

namespace Application\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class QueryManagerPlugin extends AbstractPlugin {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke($queryManager) {
        if (class_exists($queryManager)) {
            return new $queryManager($this->entityManager);
        }

        return NULL;
    }

}
