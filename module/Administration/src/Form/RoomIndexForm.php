<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RoomIndexForm extends Form {

    public function __construct($roomIds) {
        parent::__construct('room-form');

        $this->setAttribute('method', 'post');

        $this->addElements($roomIds);
    }

    protected function addElements($roomIds) {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'rooms',
            'options' => [
                'value_options' => $roomIds
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
