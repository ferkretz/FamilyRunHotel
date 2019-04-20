<?php

namespace Application\Form\Profile\Account;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
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
            'type' => Element\Email::class,
            'name' => 'email',
            'options' => [
                'label' => 'Email address / login',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'realName',
            'options' => [
                'label' => 'Full name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'displayName',
            'options' => [
                'label' => 'Display name',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'address',
            'options' => [
                'label' => 'Address',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Tel::class,
            'name' => 'phone',
            'options' => [
                'label' => 'Phone numbers',
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
            'name' => 'email',
            'required' => TRUE,
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
                        'max' => 160,
                    ],
                ],
                    [
                    'name' => Validator\EmailAddress::class
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'realName',
            'required' => TRUE,
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
                        'max' => 60,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'displayName',
            'required' => FALSE,
            'filters' => [
                    [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                    [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'max' => 60,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'address',
            'required' => TRUE,
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
                        'max' => 160,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'phone',
            'required' => TRUE,
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
    }

}
