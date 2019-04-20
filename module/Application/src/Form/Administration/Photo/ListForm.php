<?php

namespace Application\Form\Administration\Photo;

use Zend\Form\Element;
use Zend\Form\Form;

class ListForm extends Form {

    private $photoIds;

    public function __construct($photoIds) {
        parent::__construct('list-form');

        $this->photoIds = $photoIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'photos',
            'options' => [
                'value_options' => $this->photoIds
            ]
        ]);
    }

}
