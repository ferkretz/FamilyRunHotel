<?php

namespace Application\Form\Profile\Look;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Service\Option\SettingsManager;

class IndexForm extends Form {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager) {
        parent::__construct('index-form');

        $this->settingsManager = $settingsManager;

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $this->add([
            'type' => Element\Select::class,
            'name' => 'theme',
            'options' => [
                'label' => 'Theme',
                'value_options' => $this->settingsManager->getSetting(SettingsManager::THEME_NAMES),
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Select::class,
            'name' => 'barStyle',
            'options' => [
                'label' => 'Navigation bar style',
                'value_options' => $this->settingsManager->getSetting(SettingsManager::BAR_STYLE_NAMES),
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }

}
