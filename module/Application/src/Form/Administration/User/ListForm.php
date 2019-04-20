<?php

namespace Application\Form\Administration\User;

use Zend\Form\Element;
use Zend\Form\Form;

class ListForm extends Form {

    private $userIds;

    public function __construct($userIds) {
        parent::__construct('list-form');

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
    }

}
