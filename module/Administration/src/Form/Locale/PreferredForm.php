<?php

namespace Administration\Form\Locale;

use Zend\Form\Form;
use Zend\Form\Element;

class PreferredForm extends Form {

    private $localeIds;

    public function __construct($localeIds) {
        parent::__construct('preferred-form');

        $this->localeIds = $localeIds;

        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    private function addElements() {
        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'locales',
            'options' => [
                'value_options' => $this->localeIds,
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
