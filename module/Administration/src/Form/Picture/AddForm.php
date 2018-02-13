<?php

namespace Administration\Form\Picture;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Application\Filter\ConvertUpload;

class AddForm extends Form {

    protected $uploadOptions;

    public function __construct($uploadOptions) {
        parent::__construct('picture-form');

        $this->uploadOptions = $uploadOptions;

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
                'label_attributes' => ['class' => 'control-label'],
            ],
            'attributes' => [
                'class' => 'btn btn-default',
                'multiple' => TRUE,
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'upload',
            'attributes' => [
                'value' => 'Upload',
                'id' => 'upload',
                'class' => 'btn btn-primary',
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
                        'mimeType' => ['image/jpeg', 'image/png', 'image/gif'],
                    ],
                ],
                [
                    'name' => Validator\File\IsImage::class
                ],
                [
                    'name' => Validator\File\ImageSize::class,
                    'options' => [
                        'minWidth' => $this->uploadOptions['minImageSize'],
                        'minHeight' => $this->uploadOptions['minImageSize'],
                        'maxWidth' => $this->uploadOptions['maxImageSize'],
                        'maxHeight' => $this->uploadOptions['maxImageSize']
                    ],
                ],
            ],
            'filters' => [
                [
                    'name' => ConvertUpload::class,
                    'options' => [
                        'jpegQuality' => $this->uploadOptions['jpegQuality'],
                        'pictureEntityManager' => $this->uploadOptions['pictureEntityManager'],
                    ],
                ],
            ],
        ]);
    }

}
