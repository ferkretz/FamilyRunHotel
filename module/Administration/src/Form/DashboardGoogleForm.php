<?php

namespace Administration\Form;

use Zend\Filter;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\I18n\Validator as I18nValidator;

class DashboardGoogleForm extends Form {

    public function __construct() {
        parent::__construct('dashboard-form');

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'latitude',
            'options' => [
                'label' => 'Latitude coord.',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
                'onchange' => 'formatFloatInput(this,"' . localeconv()['decimal_point'] . '",7)',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'longitude',
            'options' => [
                'label' => 'Longitude coord.',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
                'onchange' => 'formatFloatInput(this,"' . localeconv()['decimal_point'] . '",7)',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'zoom',
            'options' => [
                'label' => 'Zoom',
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'latitude',
            'required' => FALSE,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'longitude',
            'required' => FALSE,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsFloat::class,
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'zoom',
            'required' => FALSE,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => I18nValidator\IsInt::class,
                ],
            ],
        ]);
    }

}
