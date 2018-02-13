<?php

namespace Administration\Form\User;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class AddForm extends Form {

    private $roles;

    public function __construct($roles) {
        parent::__construct('add-form');

        $this->roles = $roles;

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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Select::class,
            'name' => 'role',
            'options' => [
                'label' => 'Role',
                'label_attributes' => ['class' => 'control-label'],
                'value_options' => $this->roles,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'add',
            'attributes' => [
                'value' => 'Add',
                'id' => 'add',
                'class' => 'btn btn-primary',
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
        $inputFilter->add([
            'name' => 'password',
            'required' => FALSE,
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 6,
                        'max' => 60,
                    ],
                ],
            ],
        ]);
    }

}
