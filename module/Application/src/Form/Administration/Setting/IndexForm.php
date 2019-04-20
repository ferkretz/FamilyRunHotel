<?php

namespace Application\Form\Administration\Setting;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class IndexForm extends Form {

    public function __construct() {
        parent::__construct('index-form');

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'companyName',
            'options' => [
                'label' => 'Brand name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'companyFullName',
            'options' => [
                'label' => 'Full company name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Email::class,
            'name' => 'companyEmail',
            'options' => [
                'label' => 'Email address',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'companyAddress',
            'options' => [
                'label' => 'Address',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Tel::class,
            'name' => 'companyPhone',
            'options' => [
                'label' => 'Phone numbers',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'currency',
            'options' => [
                'label' => 'Currency',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'jpegQuality',
            'options' => [
                'label' => 'Uploaded Jpeg quality',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'photoMinSize',
            'options' => [
                'label' => 'Minimum size of uploadable photo',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'photoMaxSize',
            'options' => [
                'label' => 'Maximum size of uploadable photo',
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
            'name' => 'companyName',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 30,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'companyFullName',
            'required' => false,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 30,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'companyEmail',
            'required' => false,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 160,
                    ],
                ],
                [
                    'name' => Validator\EmailAddress::class
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'companyAddress',
            'required' => false,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 300,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'companyPhone',
            'required' => false,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 30,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'currency',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 10,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'jpegQuality',
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
                    'name' => Validator\Between::class,
                    'options' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'photoMinSize',
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
            'name' => 'photoMaxSize',
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
