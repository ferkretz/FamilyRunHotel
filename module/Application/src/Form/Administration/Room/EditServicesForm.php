<?php

namespace Application\Form\Administration\Room;

use Zend\Form\Element;
use Zend\Form\Form;

class EditServicesForm extends Form {

    private $serviceIds;

    public function __construct($serviceIds) {
        parent::__construct('edit-services-form');

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
    }

}
