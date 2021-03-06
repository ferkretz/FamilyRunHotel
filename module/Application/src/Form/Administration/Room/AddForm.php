<?php

namespace Application\Form\Administration\Room;

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

    protected function addElements() {
        $this->add([
            'type' => Element\Button::class,
            'name' => 'locale',
            'options' => [
                'label' => 'Locale',
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
            'required' => FALSE,
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
            'required' => TRUE,
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
            'required' => FALSE,
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
