<?php

namespace Application\Form\Index;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class LoginForm extends Form {

    public function __construct() {
        parent::__construct('login-form');

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
                'autofocus' => TRUE,
            ],
        ]);

        $this->add([
            'type' => Element\Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'rememberMe',
            'options' => [
                'label' => 'Remember me',
                'label_attributes' => ['class' => 'custom-control-label'],
            ],
            'attributes' => [
                'id' => 'rememberMe',
                'class' => 'custom-control-input',
            ],
        ]);

        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'redirectUrl'
        ]);

        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
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
            'name' => 'password',
            'required' => TRUE,
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'remember_me',
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
            'name' => 'redirect_url',
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
                        'min' => 0,
                        'max' => 2048,
                    ],
                ],
            ],
        ]);
    }

}
