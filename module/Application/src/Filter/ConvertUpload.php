<?php

namespace Application\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;
use Application\Entity\Picture\PictureEntity;

class ConvertUpload extends AbstractFilter {

    protected $options = [
        'jpegQuality' => 75,
        'pictureEntityManager' => NULL,
    ];

    public function __construct($options = []) {
        if (!empty($options)) {
            $this->options = $options;
        }
    }

    public function setPictureEntityManager($pictureEntityManager) {
        $this->options['pictureEntityManager'] = $pictureEntityManager;

        return $this;
    }

    public function setJpegQuality($jpegQuality) {
        $this->options['jpegQuality'] = $jpegQuality;

        return $this;
    }

    public function getPictureEntityManager() {
        return $this->options['pictureEntityManager'];
    }

    public function getJpegQuality() {
        return $this->options['jpegQuality'];
    }

    public function filter($value) {
        if (!is_scalar($value) && !is_array($value)) {
            return $value;
        }

        $sourceFile = $value['tmp_name'];

        if (!file_exists($sourceFile)) {
            throw new Exception\InvalidArgumentException(
            sprintf("File '%s' could not opened.", $sourceFile)
            );
        }

        $this->convertImage($sourceFile, $this->options['jpegQuality']);
    }

    protected function convertImage($srcFile,
                                    $quality = 75) {
        switch (exif_imagetype($srcFile)) {
            case IMAGETYPE_PNG:
                $tmpImg = imagecreatefrompng($srcFile);
                break;
            case IMAGETYPE_JPEG:
                $tmpImg = imagecreatefromjpeg($srcFile);
                break;
            case IMAGETYPE_GIF:
                $tmpImg = imagecreatefromgif($srcFile);
                break;
            case IMAGETYPE_BMP:
                $tmpImg = imagecreatefrombmp($srcFile);
                break;
            default:
                $tmpImg = imagecreatefromjpeg($srcFile);
                break;
        }

        if ($tmpImg && $this->options['pictureEntityManager']) {
            ob_start();
            imagejpeg($tmpImg, NULL, $quality);
            $contents = ob_get_contents();
            ob_get_clean();

            $pictureEntity = new PictureEntity();
            $pictureEntity->setContent($contents);
            $pictureEntity->setUploaded(new \DateTime('now', new \DateTimeZone('UTC')));
            $this->options['pictureEntityManager']->insert($pictureEntity);
        }

        imagedestroy($tmpImg);
    }

}
