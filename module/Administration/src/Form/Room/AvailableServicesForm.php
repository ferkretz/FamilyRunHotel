<?php

namespace Administration\Form\Room;

use Zend\Form\Form;
use Zend\Form\Element;

class AvailableServicesForm extends Form {

    private $serviceIds;

    public function __construct($serviceIds) {
        parent::__construct('available-service-form');

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
            'name' => 'add',
            'attributes' => [
                'value' => 'Add selected',
                'id' => 'add',
                'class' => 'btn btn-default btn-sm',
                'style' => 'display:none',
            ],
        ]);
    }

}
