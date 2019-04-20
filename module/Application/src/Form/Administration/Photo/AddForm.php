<?php

namespace Application\Form\Administration\Photo;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Application\Service\Option\SettingsManager;

class AddForm extends Form {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager) {
        parent::__construct('add-form');

        $this->settingsManager = $settingsManager;

        $this->setAttributes([
            'method', 'post',
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ]);

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {
        $this->add([
            'type' => Element\File::class,
            'name' => 'file',
            'options' => [
                'label' => 'File upload',
            ],
            'attributes' => [
                'class' => 'form-control-file',
                'multiple' => true,
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'file',
            'required' => TRUE,
            'validators' => [
                [
                    'name' => Validator\File\UploadFile::class
                ],
                [
                    'name' => Validator\File\MimeType::class,
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'],
                    ],
                ],
                [
                    'name' => Validator\File\IsImage::class
                ],
                [
                    'name' => Validator\File\ImageSize::class,
                    'options' => [
                        'minWidth' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MIN_SIZE),
                        'minHeight' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MIN_SIZE),
                        'maxWidth' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MAX_SIZE),
                        'maxHeight' => $this->settingsManager->getSetting(SettingsManager::PHOTO_MAX_SIZE),
                    ],
                ],
            ],
        ]);
    }

}
