<?php

namespace Administration\Form\Dashboard;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\I18n\Validator as I18nValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FeaturesForm extends Form {

    public function __construct() {
        parent::__construct('company-form');

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
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'companyI18n',
            'options' => [
                'label' => 'Translation',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'icon-checkbox',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'companyFullName',
            'options' => [
                'label' => 'Full company name',
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
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
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'companyCurrency',
            'options' => [
                'label' => 'Currency',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'uploadJpegQuality',
            'options' => [
                'label' => 'Jpeg quality',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'uploadMinImageSize',
            'options' => [
                'label' => 'Smallest image',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'uploadMaxImageSize',
            'options' => [
                'label' => 'Largest image',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'uploadThumbnailWidth',
            'options' => [
                'label' => 'Thumbnail width',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);


        $this->add([
            'type' => Element\Submit::class,
            'name' => 'save',
            'attributes' => [
                'value' => 'Save',
                'id' => 'save',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'companyName',
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
            'name' => 'companyI18n',
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
            'name' => 'companyFullName',
            'required' => FALSE,
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
            'required' => FALSE,
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
            'required' => FALSE,
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
            'required' => FALSE,
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
            'name' => 'companyCurrency',
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
                        'max' => 10,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'uploadJpegQuality',
            'required' => TRUE,
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
            'name' => 'uploadMinImageSize',
            'required' => TRUE,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'uploadMaxImageSize',
            'required' => TRUE,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class
                ],
            ],
        ]);
    }

}
