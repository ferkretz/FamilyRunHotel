<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\SiteOptionManager;
use Application\Service\ThemeSelector;

class ThemeSelectorFactory {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $optionManager = $container->get(SiteOptionManager::class);

        $themesDir = './public/themes';
        $supportedThemes = [];

        if (is_dir($themesDir)) {
            $handle = opendir($themesDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != "." && $entry != "..") {
                        $supportedThemes[] = $entry;
                    }
                }
                closedir($handle);
            }
        }

        return new ThemeSelector($optionManager, $supportedThemes);
    }

}
