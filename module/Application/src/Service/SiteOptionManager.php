<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Application\Entity\SiteOption;
use Authentication\Service\CurrentUserManager;

class SiteOptionManager {

    /**
     * Entity manager.
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Current User manager.
     * @var CurrentUserManager
     */
    protected $currentUserManager;

    /**
     * Contents of the 'site_options' config key.
     * @var array
     */
    protected $defaultSiteOptions;

    public function __construct(EntityManager $entityManager,
                                CurrentUserManager $currentUserManager,
                                $defaultSiteOptions) {
        $this->entityManager = $entityManager;
        $this->currentUserManager = $currentUserManager;
        $this->defaultSiteOptions = $defaultSiteOptions;
    }

    public function findByName($name) {
        return $this->entityManager->getRepository(SiteOption::class)->findOneBy(['name' => $name]);
    }

    public function findValueById($id,
                                  $defaultValue = NULL) {
        $option = $this->entityManager->getRepository(SiteOption::class)->find($id);
        return $option == NULL ? $defaultValue : $option->getValue();
    }

    public function findDefaultValueByName($name) {
        if (isset($this->defaultSiteOptions)) {
            return $this->defaultSiteOptions[$name] ?? NULL;
        }

        return NULL;
    }

    public function findValueByName($name,
                                    $defaultValue = NULL) {
        $option = $this->entityManager->getRepository(SiteOption::class)->findOneBy(['name' => $name]);
        $optionValue = $option ? $option->getValue() : $this->findDefaultValueByName($name);

        return $optionValue ?? $defaultValue;
    }

    public function findCurrentValueByName($name,
                                           $defaultValue = NULL) {
        $currentUser = $this->currentUserManager->get();
        if ($currentUser) {
            $currentValue = $currentUser->getOptionValue($name);
            if ($currentValue) {
                return $currentValue;
            }
        }

        return $this->findValueByName($name, $defaultValue);
    }

    public function findByQuery(Query $query) {
        return $query->getResult();
    }

    public function add(SiteOption $option) {
        $this->entityManager->persist($option);
        $this->entityManager->flush();
    }

    public function update() {
        $this->entityManager->flush();
    }

    public function remove(SiteOption $option) {
        $this->entityManager->remove($option);
        $this->entityManager->flush();
    }

}
