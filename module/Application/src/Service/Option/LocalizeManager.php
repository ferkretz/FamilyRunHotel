<?php

namespace Application\Service\Option;

use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;
use Application\Service\Option\SettingsManager;

class LocalizeManager {

    /**
     * Settings manager
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Mvc translator
     * @var Translator
     */
    protected $translator;

    public function __construct(SettingsManager $settingsManager,
                                Translator $translator) {
        $this->settingsManager = $settingsManager;
        $this->translator = $translator;
    }

    public function __invoke(?string $locale = null): string {
        $currentLocale = $locale ?? $this->settingsManager->getSetting(SettingsManager::LOCALE);

        if (!\Locale::setDefault($currentLocale)) {
            return null;
        }

        $languageDir = __DIR__ . '/../../../../../data/language/' . $currentLocale;

        if (is_dir($languageDir)) {
            $handle = opendir($languageDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== false) {
                    if ($entry != '.' && $entry != '..') {
                        if (strrpos($entry, '.php') === (strlen($entry) - 4)) {
                            $this->translator->addTranslationFile('phpArray', $languageDir . '/' . $entry, 'default', $currentLocale);
                        }
                    }
                }
                closedir($handle);
            }
        }

        AbstractValidator::setDefaultTranslator($this->translator);

        return $currentLocale;
    }

}
