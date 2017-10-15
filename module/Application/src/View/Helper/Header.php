<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Model\HeaderData;

class Header extends AbstractHelper {

    public function __invoke(HeaderData $headerData = NULL) {
        if ($headerData) {
            return $this->render($headerData);
        }

        return $this;
    }

    public function render(HeaderData $headerData) {
        if (!$headerData->getVisible()) {
            return '';
        }
        $headerData->setVisible(FALSE);

        $escapeHtml = $this->getView()->plugin('escapeHtml');

        // date formatter by default locale
        $dateFormatter = \IntlDateFormatter::create(\Locale::getDefault(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $date = $dateFormatter->format(new \DateTime());

        $html = '<div class="well header">'
                . '<canvas id="clock" width="100" height="100"></canvas>'
                . '<div class="title">'
                . '<div class="brand" href="#">' . $escapeHtml($headerData->getBrandName()) . '</div>'
                . '<div class="date">' . $escapeHtml($date) . '</div>'
                . '</div>'
                . '</div>';

        return $html;
    }

}
