<?php

namespace Profile\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class SettingsLookForm extends Form {

    public function __construct() {
        parent::__construct('settings-form');

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
            'name' => 'lookTheme',
            'options' => [
                'label' => 'Theme',
                'label_attributes' => ['class' => 'control-label'],
                'value_options' => $this->getSupportedThemeNames(),
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Select::class,
            'name' => 'lookBarStyle',
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
            'name' => 'lookRenderHeader',
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
            'name' => 'save',
            'attributes' => [
                'value' => 'Save',
                'id' => 'save',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }

    private function getSupportedThemeNames() {
        $encoding = 'UTF-8';
        $themesDir = __DIR__ . '/../../../../public/themes';
        $supportedThemeNames = [];

        if (is_dir($themesDir)) {
            $handle = opendir($themesDir);
            if ($handle) {
                while (($entry = readdir($handle)) !== FALSE) {
                    if ($entry != '.' && $entry != '..') {
                        $themeName = mb_strtoupper(mb_substr($entry, 0, 1, $encoding), $encoding) .
                                mb_substr($entry, 1, mb_strlen($entry, $encoding), $encoding);
                        $supportedThemeNames[$entry] = $themeName;
                    }
                }
                closedir($handle);
            }
        }

        return $supportedThemeNames;
    }

}
