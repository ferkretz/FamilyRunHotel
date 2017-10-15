<?php

namespace Profile\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class SettingsLookForm extends Form {

    public function __construct($themeNames) {
        parent::__construct('settings-form');

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements($themeNames);
        $this->addInputFilter();
    }

    protected function addElements($themeNames) {
        $this->add([
            'type' => Element\Select::class,
            'name' => 'theme',
            'options' => [
                'label' => 'Theme',
                'label_attributes' => ['class' => 'control-label'],
                'value_options' => $themeNames,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Select::class,
            'name' => 'navBarStyle',
            'options' => [
                'label' => 'Navigation bar style',
                'label_attributes' => ['class' => 'control-label'],
                'value_options' => ['default' => 'Light', 'inverse' => 'Dark'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Select::class,
            'name' => 'headerShow',
            'options' => [
                'label' => 'Show header',
                'label_attributes' => ['class' => 'control-label'],
                'value_options' => ['home' => 'Home', 'everywhere' => 'Everywhere', 'nowhere' => 'Nowhere'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }

}
