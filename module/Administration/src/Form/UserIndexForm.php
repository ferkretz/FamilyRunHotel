<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class UserIndexForm extends Form {

    public function __construct($userIds) {
        parent::__construct('user-form');

        $this->setAttribute('method', 'post');

        $this->addElements($userIds);
    }

    protected function addElements($userIds) {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'users',
            'options' => [
                'value_options' => $userIds
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
