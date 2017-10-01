<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class TranslatorPlugin extends AbstractPlugin {

    /**
     * Translator.
     * @var Translator
     */
    protected $translator;

    function __construct(Translator $translator) {
        $this->translator = $translator;
    }

    public function translate($string) {
        return $this->translator->translate($string);
    }

}
