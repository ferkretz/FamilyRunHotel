<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Controller\IndexController;
use Application\Service\OptionManager;

class Header extends AbstractHelper {

    /**
     * Entity manager.
     * @var OtionManager
     */
    protected $optionManager;

    /**
     * Controller.
     * @var string
     */
    protected $controller;

    public function __construct(OptionManager $optionManager,
                                $controller) {
        $this->optionManager = $optionManager;
        $this->controller = $controller;
    }

    /**
     * Renders the header.
     * @return string HTML code of the menu.
     */
    public function render() {   
        // everywhere or only index page?
        $showHeaderEveryWhere = $this->optionManager->findByName('show_header_everywhere', 'FALSE');
        if ($showHeaderEveryWhere == 'FALSE') {
            if ($this->controller != IndexController::class) {
                return '';
            }
        }

        // date formatter by default locale
        $dateFormatter = \IntlDateFormatter::create(\Locale::getDefault(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        // brand name or 'FamilyRunHotel'
        $brandName = $this->optionManager->findByName('brand_name', 'FamilyRunHotel');

        $result = '<div class="header header-inverse col-sm-12 center-block">'
                . '<canvas id="clock" width="112" height="112"></canvas>'
                . '<div class="title">'
                . '<div class="brand" href="#">' . $brandName . '</div>'
                . '<div class="date">' . $dateFormatter->format(new \DateTime()) . '</div>'
                . '</div>'
                . '</div>';

        return $result;
    }

}
