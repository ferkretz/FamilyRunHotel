<?php

namespace Administration\Form\Room;

use Zend\Form\Form;
use Zend\Form\Element;

class AvailablePicturesForm extends Form {

    private $pictureIds;

    public function __construct($pictureIds) {
        parent::__construct('available-picture-form');

        $this->pictureIds = $pictureIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'pictures',
            'options' => [
                'value_options' => $this->pictureIds,
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'add',
            'attributes' => [
                'value' => 'Add selected',
                'id' => 'add',
                'class' => 'btn btn-default btn-sm',
                'style' => 'display:none'
            ],
        ]);
    }

}
