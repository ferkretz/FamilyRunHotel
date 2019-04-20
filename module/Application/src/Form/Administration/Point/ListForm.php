<?php

namespace Application\Form\Administration\Point;

use Zend\Form\Element;
use Zend\Form\Form;

class ListForm extends Form {

    private $pointIds;

    public function __construct($pointIds) {
        parent::__construct('list-form');

        $this->pointIds = $pointIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'points',
            'options' => [
                'value_options' => $this->pointIds
            ]
        ]);
    }

}
