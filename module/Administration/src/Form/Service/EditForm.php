<?php

namespace Administration\Form\Service;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\I18n\Validator as I18nValidator;

class EditForm extends Form {

    public function __construct() {
        parent::__construct('room-service-form');

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
            'type' => Element\Text::class,
            'name' => 'price',
            'options' => [
                'label' => 'Price (%s)',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
                'onchange' => 'formatFloatInput(this,"' . $decimalSeparator . '",2)',
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
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'edit',
            'attributes' => [
                'value' => 'Edit',
                'id' => 'edit',
                'class' => 'btn btn-primary',
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
                    'options' => [
                        'max' => 20,
                    ],
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
