<?php

namespace Application\View\Helper\Site;

use Zend\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;

class Header extends AbstractHelper {

    protected $routeMatch;
    protected $lookConfig;
    protected $companyConfig;

    public function __construct(RouteMatch $routeMatch,
                                $lookConfig,
                                $companyConfig) {
        $this->routeMatch = $routeMatch;
        $this->lookConfig = $lookConfig;
        $this->companyConfig = $companyConfig;
    }

    public function render() {
        if ($this->lookConfig['renderHeader'] == 'nowhere') {
            return '';
        }
        if (($this->lookConfig['renderHeader'] == 'home') && ($this->routeMatch->getMatchedRouteName() != 'homeIndex')) {
            return '';
        }

        $escapeHtml = $this->getView()->plugin('escapeHtml');
        $translate = $this->getView()->plugin('translate');

        // date formatter by default locale
        $dateFormatter = \IntlDateFormatter::create(\Locale::getDefault(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $date = $dateFormatter->format(new \DateTime());
        $label = $this->companyConfig['i18n'] ? $translate($this->companyConfig['name']) : $this->companyConfig['name'];

        $html = '<div class="well header">'
                . '<canvas id="clock" width="100" height="100"></canvas>'
                . '<div class="title">'
                . '<div class="head" href="#">' . $escapeHtml($label) . '</div>'
                . '<div class="date">' . $escapeHtml($date) . '</div>'
                . '</div>'
                . '</div>';

        return $html;
    }

}
