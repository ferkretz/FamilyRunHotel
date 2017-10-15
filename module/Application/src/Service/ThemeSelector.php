<?php

namespace Application\Service;

use Application\Service\SiteOptionManager;

class ThemeSelector {

    /**
     * Picture manager.
     * @var OptionManager
     */
    protected $optionManager;
    protected $supportedThemes;

    public function __construct(SiteOptionManager $optionManager,
                                array $supportedThemes) {
        $this->optionManager = $optionManager;
        $this->supportedThemes = $supportedThemes;
    }

    function getSupportedThemeNames() {
        $supportedThemeNames = [];

        foreach ($this->supportedThemes as $theme) {
            $supportedThemeNames[$theme] = mb_ucfirst($theme);
        }

        return $supportedThemeNames;
    }

    function getLocalTheme() {
        $theme = $this->optionManager->findCurrentValueByName('theme');
        return in_array($theme, $this->supportedThemes) ? $theme : $this->supportedThemes[0];
    }

}
