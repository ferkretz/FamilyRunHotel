<?php

namespace Application\View\Helper\Site;

use Zend\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;

class HeaderHelper extends AbstractHelper {

    protected $lookRenderHeader;
    protected $companyName;
    protected $routeMatch;

    public function __construct(string $lookRenderHeader,
                                string $companyName,
                                RouteMatch $routeMatch) {
        $this->lookRenderHeader = $lookRenderHeader;
        $this->companyName = $companyName;
        $this->routeMatch = $routeMatch;
    }

    public function __invoke() {
        if ($this->lookRenderHeader == 'nowhere') {
            return '';
        }
        if (($this->lookRenderHeader == 'home') && ($this->routeMatch->getMatchedRouteName() != 'homeIndex')) {
            return '';
        }

        $escapeHtml = $this->getView()->plugin('escapeHtml');

        // date formatter by default locale
        $dateFormatter = \IntlDateFormatter::create(\Locale::getDefault(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $date = $dateFormatter->format(new \DateTime());

        $html = '<div class="well header">'
                . '<canvas id="clock" width="100" height="100"></canvas>'
                . '<div class="title">'
                . '<div class="head" href="#">' . $escapeHtml($this->companyName) . '</div>'
                . '<div class="date">' . $escapeHtml($date) . '</div>'
                . '</div>'
                . '</div>';

        return $html;
    }

}
