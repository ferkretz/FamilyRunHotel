<?php

namespace Application\Service\Site;

use Application\Service\Site\SiteOptionEntityManager;
use Application\Service\User\CurrentUserEntityManager;

class CurrentOptionValueManager {

    /**
     * Site option manager.
     * @var SiteOptionEntityManager
     */
    protected $siteOptionEntityManager;

    /**
     * Current User Entity manager.
     * @var CurrentUserEntityManager
     */
    protected $currentUserEntityManager;

    /**
     * Contents of the 'site' config key.
     * @var array
     */
    protected $siteConfig;

    public function __construct(SiteOptionEntityManager $siteOptionEntityManager,
                                CurrentUserEntityManager $currentUserEntityManager,
                                array $siteConfig) {
        $this->siteOptionEntityManager = $siteOptionEntityManager;
        $this->currentUserEntityManager = $currentUserEntityManager;
        $this->siteConfig = $siteConfig;
    }

    public function findOneByName($name,
                                  $defaultValue = NULL) {
        if (!is_array($defaultValue)) {
            $defaultValue = [];
        }

        // search for user option
        $currentUserEntity = $this->currentUserEntityManager->get();
        if ($currentUserEntity) {
            $userOptionEntity = $currentUserEntity->getOptions()->get($name);
            if ($userOptionEntity) {
                $currentValue = unserialize($userOptionEntity->getValue());
            }
        }

        // search for site option
        $siteOptionEntity = $this->siteOptionEntityManager->findOneByName($name);
        if ($siteOptionEntity) {
            $siteValue = unserialize($siteOptionEntity->getValue());
        }

        if ($this->siteConfig[$name]) {
            $defaultValue = array_merge($defaultValue, $this->siteConfig[$name]);
        }

        if ($defaultValue) {
            $siteValue = array_merge($defaultValue, $siteValue ?? []);
        }

        if ($siteValue) {
            $currentValue = array_merge($siteValue, $currentValue ?? []);
        }

        return $currentValue;
    }

}
