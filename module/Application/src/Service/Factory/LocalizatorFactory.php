<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mvc\I18n\Translator;
use Application\Service\Localizator;

class LocalizatorFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $headers = $container->get('Request')->getHeaders();
        $config = $container->get('Config');
        $translator = $container->get(Translator::class);

        // locales from browsers
        $acceptedLocales = [];
        if ($headers->has('Accept-Language')) {
            $languages = $headers->get('Accept-Language')->getPrioritized();
            foreach ($languages as $language) {
                $language = str_replace('-', '_', $language->typeString);
                $acceptedLocales[] = $language;
            }
        }

        // original OS locale
        $supportedLocales = [setlocale(LC_ALL, '')];
        \Locale::setDefault($supportedLocales[0]);

        // probe fallback locale
        if ($config['language_config']) {
            $fallbackLocale = $config['language_config']['fallback_locale'];
            if (setlocale(LC_ALL, $fallbackLocale) != FALSE) {
                $supportedLocales[0] = $fallbackLocale;
                \Locale::setDefault($supportedLocales[0]);
            }
        }

        // search in locale directory
        $languagesDir = $config['language_config']['languages_dir'];
        if (is_dir($languagesDir)) {
            $handle = opendir($languagesDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != "." && $entry != "..") {
                        $supportedLocales[] = $entry;
                    }
                }
                closedir($handle);
            }
        }

        return new Localizator($translator, $languagesDir, $acceptedLocales, $supportedLocales);
    }

}
