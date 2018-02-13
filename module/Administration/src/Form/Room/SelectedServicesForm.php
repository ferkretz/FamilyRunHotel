<?php

namespace Administration\Form\Room;

use Zend\Form\Form;
use Zend\Form\Element;

class SelectedServicesForm extends Form {

    private $serviceIds;

    public function __construct($serviceIds) {
        parent::__construct('selected-service-form');

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
