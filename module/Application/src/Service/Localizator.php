<?php

namespace Application\Service;

use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;

class Localizator {

    protected $translator;
    protected $languagesDir;
    protected $acceptedLocales;
    protected $supportedLocales;

    public function __construct(Translator $translator,
                                string $languagesDir,
                                array $acceptedLocales,
                                array $supportedLocales) {
        $this->translator = $translator;
        $this->languagesDir = $languagesDir;
        $this->acceptedLocales = $acceptedLocales;
        $this->supportedLocales = $supportedLocales;

        $this->setBestLocale();
        $this->setTranslator();
    }

    function getTranslator() {
        return $this->translator;
    }

    function getAcceptedLocales() {
        return $this->acceptedLocales;
    }

    function getAcceptedLocaleNames() {
        $locales = [];

        foreach ($this->acceptedLocales as $acceptedLocale) {
            $locales[$acceptedLocale] = \Locale::getDisplayName($acceptedLocale);
        }

        return $locales;
    }

    function getFallbackLocale() {
        return $this->supportedLocales[0];
    }

    function getFallbackLocaleName() {
        $locale = [];

        $locale[$this->supportedLocales[0]] = \Locale::getDisplayName($this->supportedLocales[0]);

        return $locale;
    }

    function getSupportedLocales($includeFallback = FALSE) {
        return array_slice($this->supportedLocales, $includeFallback ? 0 : 1);
    }

    function getSupportedLocaleNames($includeFallback = FALSE) {
        $locales = [];

        foreach (array_slice($this->supportedLocales, $includeFallback ? 0 : 1) as $supportedLocale) {
            $locales[$supportedLocale] = \Locale::getDisplayName($supportedLocale);
        }

        return $locales;
    }

    protected function changeLocale($locale) {
        // nothing to change
        if ($locale == \Locale::getDefault()) {
            return $locale;
        }

        // unsuccess
        if (setlocale(LC_ALL, $locale) == FALSE) {
            return FALSE;
        }
        \Locale::setDefault($locale);
        $this->currentLocale = $locale;

        $this->setTranslator();

        return $locale;
    }

    protected function setBestLocale() {
        foreach ($this->acceptedLocales as $acceptedLocale) {
            $acceptedPrimary = \Locale::getPrimaryLanguage($acceptedLocale);
            $acceptedRegion = \Locale::getRegion($acceptedLocale);

            // collected primary match
            $supportedRegions = [];
            foreach ($this->supportedLocales as $supportedLocale) {
                $supportedPrimary = \Locale::getPrimaryLanguage($supportedLocale);
                $supportedRegion = \Locale::getRegion($supportedLocale);

                if ($acceptedPrimary == $supportedPrimary) {
                    $supportedRegions[] = $supportedRegion;
                }
            }

            foreach ($supportedRegions as $supportedRegion) {
                if (empty($acceptedRegion)) {
                    // find accept region in the list for primary
                    foreach ($this->acceptedLocales as $acceptedLocale2) {
                        $acceptedPrimary2 = \Locale::getPrimaryLanguage($acceptedLocale2);
                        $acceptedRegion2 = \Locale::getRegion($acceptedLocale2);

                        if (($acceptedPrimary == $acceptedPrimary2) && ($supportedRegion == $acceptedRegion2)) {
                            $locale = $acceptedPrimary2 . '_' . $acceptedRegion2;
                            if ($this->changeLocale($locale)) {
                                return $locale;
                            }
                        }
                    }

                    // try same region name
                    $locale = $acceptedPrimary . '_' . strtoupper($acceptedPrimary);
                    if ($this->changeLocale($locale)) {
                        return $locale;
                    }

                    // region is not obvious
                    $locale = $acceptedPrimary;
                    if ($this->changeLocale($locale)) {
                        return $locale;
                    }
                } else {
                    // exact match or primary match
                    if (($acceptedRegion == $supportedRegion) || empty($supportedRegion)) {
                        $locale = $acceptedPrimary . '_' . $acceptedRegion;
                        if ($this->changeLocale($locale)) {
                            return $locale;
                        }
                    }
                }
            }
        }

        return \Locale::getDefault();
    }

    protected function setTranslator() {
        $localeDir = NULL;
        $locale = \Locale::getDefault();

        foreach ($this->supportedLocales as $supportedLocale) {
            if ($supportedLocale == $locale) {
                $localeDir = $this->languagesDir . $locale;
                break;
            }
        }
        if (!$localeDir) {
            $localeDir = $this->languagesDir . \Locale::getPrimaryLanguage($locale);
        }

        if (is_dir($localeDir)) {
            $handle = opendir($localeDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != "." && $entry != "..") {
                        if (strrpos($entry, '.php') === (strlen($entry) - 4)) {
                            $this->translator->addTranslationFile('phpArray', $localeDir . '/' . $entry, 'default', $locale);
                        }
                    }
                }
                closedir($handle);
            }
        }

        AbstractValidator::setDefaultTranslator($this->translator);
    }

}
