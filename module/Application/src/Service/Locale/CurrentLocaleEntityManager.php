<?php

namespace Application\Service\Locale;

use Application\Entity\Locale\LocaleEntity;
use Application\Service\Locale\LocaleEntityManager;

class CurrentLocaleEntityManager {

    /**
     * Locale manager.
     * @var LocaleEntityManager
     */
    protected $localeEntityManager;

    /**
     * Requested Accept-Language.
     * @var array
     */
    protected $requestedLocales;

    /**
     * Current LocaleEntity cache.
     * @var LocaleEntity
     */
    protected $currentLocaleEntity;

    public function __construct(LocaleEntityManager $localeEntityManager,
                                array $requestedLocales) {
        $this->localeEntityManager = $localeEntityManager;
        $this->requestedLocales = $requestedLocales;
        $this->currentLocaleEntity = NULL;
    }

    public function get() {
        if (!$this->currentLocaleEntity) {
            $this->currentLocaleEntity = $this->findCurrentLocaleEntity();
        }

        return $this->currentLocaleEntity;
    }

    public function clearCache() {
        $this->currentLocaleEntity = NULL;
    }

    private function findCurrentLocaleEntity() {
        $supportedLocales = $this->localeEntityManager->findAllName();

        foreach ($this->requestedLocales as $requestedLocale) {
            $currentLocale = \Locale::lookup($supportedLocales, $requestedLocale->typeString, TRUE, '.');
            if ($currentLocale != '.') {
                return $this->localeEntityManager->findOneByName($currentLocale);
            }
        }

        return $this->localeEntityManager->findOneById(1);
    }

}
