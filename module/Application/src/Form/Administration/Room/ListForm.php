<?php

namespace Application\Form\Administration\Room;

use Zend\Form\Element;
use Zend\Form\Form;

class ListForm extends Form {

    private $roomIds;

    public function __construct($roomIds) {
        parent::__construct('list-form');

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
    }

}
