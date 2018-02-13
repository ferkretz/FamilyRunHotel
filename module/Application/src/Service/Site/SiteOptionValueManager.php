<?php

namespace Application\Service\Site;

use Application\Service\Site\SiteOptionEntityManager;

class SiteOptionValueManager {

    /**
     * Site option manager.
     * @var SiteOptionEntityManager
     */
    protected $siteOptionEntityManager;

    /**
     * Contents of the 'site' config key.
     * @var array
     */
    protected $siteConfig;

    public function __construct(SiteOptionEntityManager $siteOptionEntityManager,
                                array $siteConfig) {
        $this->siteOptionEntityManager = $siteOptionEntityManager;
        $this->siteConfig = $siteConfig;
    }

    public function findOneByName($name,
                                  $defaultValue = NULL) {
        if (!is_array($defaultValue)) {
            $defaultValue = [];
        }

        $siteOptionEntity = $this->siteOptionEntityManager->findOneByName($name);
        if ($siteOptionEntity) {
            $siteValue = unserialize($siteOptionEntity->getValue());
        }

        if ($this->siteConfig[$name]) {
            $defaultValue = array_merge($defaultValue, $this->siteConfig[$name]);
        }

        if ($defaultValue) {
            return array_merge($defaultValue, $siteValue ?? []);
        }

        return $siteValue;
    }

}
