<?php

namespace Application\Form\Profile\Password;

use Zend\Filter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Application\Validator\Common as ApplicationValidator;

class IndexForm extends Form {

    protected $hash;

    public function __construct(string $hash) {
        parent::__construct('index-form');

        $this->hash = $hash;

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $this->add([
            'type' => Element\Password::class,
            'name' => 'oldPassword',
            'options' => [
                'label' => 'Old password',
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
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Password::class,
            'name' => 'confirmPassword',
            'options' => [
                'label' => 'Confirm password',
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
            'name' => 'oldPassword',
            'required' => TRUE,
            'validators' => [
                    [
                    'name' => ApplicationValidator\PasswordVerify::class,
                    'options' => [
                        'hash' => $this->hash,
                    ],
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
                        'min' => 6,
                        'max' => 60,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'confirmPassword',
            'required' => TRUE,
            'validators' => [
                    [
                    'name' => Validator\Identical::class,
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);
    }

}
