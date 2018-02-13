<?php

namespace Administration\Form\User;

use Zend\Form\Form;
use Zend\Form\Element;

class IndexForm extends Form {

    private $userIds;

    public function __construct($userIds) {
        parent::__construct('index-form');

        $this->userIds = $userIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'users',
            'options' => [
                'value_options' => $this->userIds
            ]
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'remove',
            'attributes' => [
                'value' => 'Remove selected',
                'id' => 'remove',
                'class' => 'btn btn-default btn-sm',
                'style' => 'display:none'
            ],
        ]);
    }

}
