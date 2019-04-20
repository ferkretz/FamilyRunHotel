<?php

namespace Application\Service\Option;

use Application\Entity\Option\Option;
use Application\Service\Option\OptionManager;
use Application\Service\User\AuthenticationManager;

class SettingsManager {

    const BAR_STYLE = 'barStyle';
    const BAR_STYLE_NAMES = 'barStyleNames';
    const BAR_STYLES = 'barStyles';
    const COMPANY_ADDRESS = 'companyAddress';
    const COMPANY_EMAIL = 'companyEmail';
    const COMPANY_FULL_NAME = 'companyFullName';
    const COMPANY_NAME = 'companyName';
    const COMPANY_PHONE = 'companyPhone';
    const CURRENCY = 'currency';
    const ENABLE_MAP = 'enableMap';
    const JPEG_QUALITY = 'jpegQuality';
    const LOCALE = 'locale';
    const LOCALE_NAMES = 'localeNames';
    const LOCALES = 'locales';
    const MAP_ZOOM = 'mapZoom';
    const PHOTO_MAX_SIZE = 'photoMaxSize';
    const PHOTO_MIN_SIZE = 'photoMinSize';
    const PHOTO_THUMBNAIL_WIDTH = 'photoTumbnailWidth';
    const PHOTOS_PER_PAGE = 'photosPerPage';
    const ROOMS_PER_PAGE = 'roomsPerPage';
    const ROWS_PER_PAGE = 'rowsPerPage';
    const THEME = 'theme';
    const THEME_NAMES = 'themeNames';
    const THEMES = 'themes';
    protected const DEFAULTS = [
        self::BAR_STYLE => 'light',
        self::BAR_STYLE_NAMES => ['light' => 'Light', 'dark' => 'Dark'],
        self::COMPANY_ADDRESS => '',
        self::COMPANY_EMAIL => '',
        self::COMPANY_FULL_NAME => '',
        self::COMPANY_NAME => 'Family-run Hotel',
        self::COMPANY_PHONE => '',
        self::CURRENCY => 'USD',
        self::ENABLE_MAP => 'true',
        self::JPEG_QUALITY => '75',
        self::LOCALE => 'en',
        self::MAP_ZOOM => '15',
        self::PHOTO_MAX_SIZE => '7680',
        self::PHOTO_MIN_SIZE => '256',
        self::PHOTO_THUMBNAIL_WIDTH => '196',
        self::PHOTOS_PER_PAGE => '10',
        self::ROOMS_PER_PAGE => '6',
        self::ROWS_PER_PAGE => '20',
        self::THEME => 'coffee',
    ];

    /**
     * AuthenticationManager manager.
     * @var AuthenticationManager
     */
    protected $authenticationManager;

    /**
     * Site/User option entity manager.
     * @var OptionManager
     */
    protected $optionManager;

    /**
     * Requested Accept-Language.
     * @var array
     */
    protected $requestedLocales;

    /**
     * Current settings cache
     * @var array
     */
    protected $settings = [];

    public function __construct(AuthenticationManager $authenticationManager,
                                OptionManager $optionManager,
                                array $requestedLocales) {
        $this->authenticationManager = $authenticationManager;
        $this->optionManager = $optionManager;
        $this->requestedLocales = $requestedLocales;
    }

    public function getSetting(string $name,
                               bool $default = false) {
        if ($default) {
            switch ($name) {
                case self::BAR_STYLE_NAMES:
                    return $this->getSetting(self::BAR_STYLE_NAMES);
                case self::BAR_STYLES:
                    return $this->getSetting(self::BAR_STYLES);
                case self::LOCALE_NAMES:
                    return $this->getSetting(self::LOCALE_NAMES);
                case self::LOCALES:
                    return $this->getSetting(self::LOCALES);
                case self::THEME_NAMES:
                    return $this->getSetting(self::THEME_NAMES);
                case self::THEMES:
                    return $this->getSetting(self::THEMES);
            }

            return self::DEFAULTS[$name];
        }

        if (array_key_exists($name, $this->settings)) {
            return $this->settings[$name];
        }

        switch ($name) {
            case self::BAR_STYLE_NAMES:
                return self::DEFAULTS[self::BAR_STYLE_NAMES];
            case self::BAR_STYLES:
                return array_keys(self::DEFAULTS[self::BAR_STYLE_NAMES]);
            case self::LOCALE:
                $this->settings[$name] = self::DEFAULTS[self::LOCALE];
                foreach ($this->requestedLocales as $requestedLocale) {
                    $locale = \Locale::lookup($this->getSetting(self::LOCALES), $requestedLocale->typeString, true, '.');
                    if ($locale != '.') {
                        $this->settings[$name] = $locale;
                        break;
                    }
                }
                break;
            case self::LOCALE_NAMES:
                $this->settings[$name][self::DEFAULTS[self::LOCALE]] = \Locale::getDisplayName(self::DEFAULTS[self::LOCALE]);
                foreach (glob(__DIR__ . '/../../../../../data/language/*', GLOB_ONLYDIR) as $directory) {
                    $this->settings[$name][basename($directory)] = \Locale::getDisplayName(basename($directory));
                }
                break;
            case self::LOCALES:
                return array_keys($this->getSetting(self::LOCALE_NAMES));
            case self::THEME:
                $themes = $this->getSetting(self::THEMES);
                $defaultTheme = in_array(self::DEFAULTS[self::THEME], $themes) ? self::DEFAULTS[self::THEME] : $themes[0];
                $this->settings[$name] = $this->getOptionValue($name, $defaultTheme);
                break;
            case self::THEME_NAMES:
                $this->settings[$name] = [];
                foreach (glob(__DIR__ . '/../../../../../public/themes/*', GLOB_ONLYDIR) as $directory) {
                    $this->settings[$name][basename($directory)] = ucfirst(basename($directory));
                }
                if (empty($this->settings[$name])) {
                    throw new \InvalidArgumentException('There are no available themes');
                }
                break;
            case self::THEMES:
                return array_keys($this->getSetting(self::THEME_NAMES));
            default:
                $this->settings[$name] = $this->getOptionValue($name, self::DEFAULTS[$name]);
        }

        return $this->settings[$name];
    }

    public function setSetting(string $name,
                               string $value) {
        if ($this->authenticationManager->getCurrentUser()) {
            $userId = $this->authenticationManager->getCurrentUser()->getId();
        }

        $this->setOptionValue($name, $value, $userId);
    }

    public function getOptionValue(string $name,
                                   ?string $defaultValue = null) {
        if ($this->authenticationManager->getCurrentUser()) {
            $userId = $this->authenticationManager->getCurrentUser()->getId();
            $userOption = $this->optionManager->findOneByName($name, $userId);
        }

        $option = $userOption ?? $this->optionManager->findOneByName($name);

        return ($option ? $option->getValue() : $defaultValue) ?? $defaultValue;
    }

    public function setOptionValue(string $name,
                                   string $value,
                                   ?int $userId = null) {
        $option = $this->optionManager->findOneByName($name, $userId ?? 0);
        if ($option) {
            $option->setValue($value);
            $this->optionManager->update($option);
        } else {
            $option = new Option();
            $option->setName($name);
            $option->setValue($value);
            $option->setUserId($userId ?? 0);
            $this->optionManager->insert($option);
        }
    }

}
