<?php

namespace Administration\Form\Room;

use Zend\Form\Form;
use Zend\Form\Element;

class IndexForm extends Form {

    private $roomIds;

    public function __construct($roomIds) {
        parent::__construct('room-form');

        $this->roomIds = $roomIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'rooms',
            'options' => [
                'value_options' => $this->roomIds
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
