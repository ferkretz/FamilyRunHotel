<?php

namespace Administration\Form\Room;

use Zend\Form\Form;
use Zend\Form\Element;

class SelectedPicturesForm extends Form {

    private $pictureIds;

    public function __construct($pictureIds) {
        parent::__construct('selected-picture-form');

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
