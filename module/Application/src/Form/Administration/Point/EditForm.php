<?php

namespace Application\Form\Administration\Point;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class EditForm extends Form {

    public function __construct() {
        parent::__construct('edit-form');

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $this->add([
            'type' => Element\Button::class,
            'name' => 'locale',
            'options' => [
                'label' => 'Locale',
            ],
            'attributes' => [
                'value' => 'Locale',
                'id' => 'locale',
                'class' => 'btn btn-default btn-sm dropdown-toggle disabled',
                'data-toggle' => 'dropdown',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'latitude',
            'options' => [
                'label' => 'Latitude',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => '-90',
                'max' => '90',
                'step' => '0.0000000001'
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'longitude',
            'options' => [
                'label' => 'Longitude',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => '-180',
                'max' => '180',
                'step' => '0.0000000001'
            ],
        ]);
        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'icon',
            'options' => [
                'label' => 'POI icons',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'summary',
            'options' => [
                'label' => 'Summary',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => 'Description',
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
            'name' => 'latitude',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                ],
                [
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => -90,
                        'max' => 90,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'longitude',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                ],
                [
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'summary',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                ['name' => Validator\NotEmpty::class],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 60,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                ['name' => Validator\NotEmpty::class],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 1024,
                    ],
                ],
            ],
        ]);
    }

}
