<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PictureIndexForm extends Form {

    public function __construct($pictureIds) {
        parent::__construct('picture-form');

        $this->setAttribute('method', 'post');

        $this->addElements($pictureIds);
    }

    protected function addElements($pictureIds) {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'pictures',
            'options' => [
                'value_options' => $pictureIds
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
