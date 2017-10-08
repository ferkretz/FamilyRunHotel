<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RoomServiceIndexForm extends Form {

    public function __construct($roomServiceIds) {
        parent::__construct('room-service-form');

        $this->setAttribute('method', 'post');

        $this->addElements($roomServiceIds);
    }

    protected function addElements($roomServiceIds) {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'roomServices',
            'options' => [
                'value_options' => $roomServiceIds
            ]
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Remove selected',
                'id' => 'submit',
            ],
        ]);
    }

}
