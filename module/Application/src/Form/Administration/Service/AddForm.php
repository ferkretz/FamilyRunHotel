<?php

namespace Application\Form\Administration\Service;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class AddForm extends Form {

    public function __construct() {
        parent::__construct('add-form');

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
            'name' => 'price',
            'options' => [
                'label' => 'Price (%s)',
            ],
            'attributes' => [
                'class' => 'form-control',
                'step' => '0.01'
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
            'name' => 'price',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\StringLength::class,
                ],
                [
                    'name' => I18nValidator\IsFloat::class,
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