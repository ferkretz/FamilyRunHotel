<?php

namespace Administration\Form\Dashboard;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class LookForm extends Form {

    public function __construct() {
        parent::__construct('look-form');

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $decimalFormatter = new \NumberFormatter(NULL, \NumberFormatter::DECIMAL);
        $decimalSeparator = $decimalFormatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

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
            'type' => Element\Checkbox::class,
            'name' => 'googleEnable',
            'options' => [
                'label' => 'Enable maps',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'icon-checkbox',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'googleLatitude',
            'options' => [
                'label' => 'Latitude coord.',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
                'onchange' => 'formatFloatInput(this,"' . $decimalSeparator . '",7)',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'googleLongitude',
            'options' => [
                'label' => 'Longitude coord.',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
                'onchange' => 'formatFloatInput(this,"' . $decimalSeparator . '",7)',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'googleZoom',
            'options' => [
                'label' => 'Zoom',
                'label_attributes' => ['class' => 'control-label'],
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
        $decimalFormatter = new \NumberFormatter(NULL, \NumberFormatter::DECIMAL);
        $decimalSeparator = $decimalFormatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'googleEnable',
            'required' => FALSE,
            'validators' => [
                [
                    'name' => Validator\InArray::class,
                    'options' => [
                        'haystack' => [0, 1],
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'googleLatitude',
            'required' => TRUE,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
                [
                    'name' => Filter\PregReplace::class,
                    'options' => [
                        'pattern' => "/$decimalSeparator/",
                        'replacement' => '.',
                    ],
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                    'options' => [
                        'locale' => 'en_US',
                    ],
                ],
                [
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => -90.0,
                        'max' => 90.0,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'googleLongitude',
            'required' => TRUE,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
                [
                    'name' => Filter\PregReplace::class,
                    'options' => [
                        'pattern' => "/$decimalSeparator/",
                        'replacement' => '.',
                    ],
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                    'options' => [
                        'locale' => 'en_US',
                    ],
                ],
                [
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => -180.0,
                        'max' => 180.0,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'googleZoom',
            'required' => TRUE,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class,
                ],
            ],
        ]);
    }

    private function getSupportedThemeNames() {
        $encoding = 'UTF-8';
        $themesDir = __DIR__ . '/../../../../../public/themes';
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
