<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Administration\Service\OptionManager;
use Application\Controller\IndexController;

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
    protected $controllerName;

    /**
     * Action.
     * @var string
     */
    protected $actionName;

    public function __construct(OptionManager $optionManager,
                                $controllerName,
                                $actionName) {
        $this->optionManager = $optionManager;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }

    /**
     * Renders the header.
     * @return string HTML code of the menu.
     */
    public function render() {
        // everywhere or only index page?
        $showHeaderEveryWhere = $this->optionManager->findByName('showHeaderEverywhere', 'FALSE');
        if ($showHeaderEveryWhere == 'FALSE') {
            if ($this->controllerName != IndexController::class || $this->actionName != 'index') {
                return '';
            }
        }

        $escapeHtml = $this->getView()->plugin('escapeHtml');

        // date formatter by default locale
        $dateFormatter = \IntlDateFormatter::create(\Locale::getDefault(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);
        $date = $dateFormatter->format(new \DateTime());

        // brand name or 'FamilyRunHotel'
        $brandName = $this->optionManager->findByName('brandName', 'FamilyRunHotel');

        $result = '<div class="well header">'
                . '<canvas id="clock" width="100" height="100"></canvas>'
                . '<div class="title">'
                . '<div class="brand" href="#">' . $escapeHtml($brandName) . '</div>'
                . '<div class="date">' . $escapeHtml($date) . '</div>'
                . '</div>'
                . '</div>';

        return $result;
    }

    protected function translate($message) {
        return $this->translator->translate($message);
    }

}
