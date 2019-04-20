<?php

namespace Application\Form\Administration\Look;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
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
        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'enableMap',
            'options' => [
                'label' => 'Enable OpenStreetMap',
                'label_attributes' => ['class' => 'custom-control-label'],
            ],
            'attributes' => [
                'id' => 'enableMap',
                'class' => 'custom-control-input',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'mapZoom',
            'options' => [
                'label' => 'Zoom',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'rowsPerPage',
            'options' => [
                'label' => 'Table rows per page',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'photosPerPage',
            'options' => [
                'label' => 'Photos per page',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'roomsPerPage',
            'options' => [
                'label' => 'Rooms per page',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'photoThumbnailWidth',
            'options' => [
                'label' => 'Width of the photo thumbnails',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'enableMap',
            'required' => TRUE,
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
            'name' => 'mapZoom',
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
                [
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'rowsPerPage',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
                [
                    'name' => Validator\GreaterThan::class,
                    'options' => [
                        'min' => 1,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'photosPerPage',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
                [
                    'name' => Validator\GreaterThan::class,
                    'options' => [
                        'min' => 1,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'roomsPerPage',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
                [
                    'name' => Validator\GreaterThan::class,
                    'options' => [
                        'min' => 1,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'photoThumbnailWidth',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
                [
                    'name' => Validator\GreaterThan::class,
                    'options' => [
                        'min' => 1,
                    ],
                ],
            ],
        ]);
    }

}
