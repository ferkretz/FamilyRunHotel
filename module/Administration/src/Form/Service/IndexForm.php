<?php

namespace Administration\Form\Service;

use Zend\Form\Form;
use Zend\Form\Element;

class IndexForm extends Form {

    private $serviceIds;

    public function __construct($serviceIds) {
        parent::__construct('service-form');

        $this->serviceIds = $serviceIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'services',
            'options' => [
                'value_options' => $this->serviceIds
            ]
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'remove',
            'attributes' => [
                'value' => 'Remove selected',
                'id' => 'remove',
                'class' => 'btn btn-default btn-sm',
                'style' => 'display:none',
            ],
        ]);
    }

}
