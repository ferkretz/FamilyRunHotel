<?php

namespace Application\Controller\Index;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\Photo\PhotoManager;
use Application\Service\User\AccessManager;

class PhotoController extends AbstractActionController {

    /**
     * Photo entity manager.
     * @var PhotoManager
     */
    protected $photoManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(PhotoManager $photoManager,
                                AccessManager $accessManager) {
        $this->photoManager = $photoManager;
        $this->accessManager = $accessManager;
    }

    public function indexAction() {
        $this->accessManager->currentUserRedirect('view.offers');

        $id = $this->params()->fromRoute('id', -1);
        $width = $this->params()->fromQuery('width', -1);
        $photo = $this->photoManager->findOneById($id);
        if (!$photo) {
            return $this->notFoundAction();
        }

        $src = imagecreatefromstring(stream_get_contents($photo->getContent()));

        if ($width > 0) {
            $srcWidth = imagesx($src);
            $srcHeight = imagesy($src);
            if ($srcWidth > $width) {
                $height = $srcHeight / $srcWidth * $width;
                $dst = imagecreatetruecolor($width, $height);
                imagecopyresized($dst, $src, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

                header("Content-type:  image/jpeg");
                imagejpeg($dst);
                imagedestroy($dst);
            } else {
                header("Content-type:  image/jpeg");
                imagejpeg($src);
                imagedestroy($src);
            }
        } else {
            header("Content-type:  image/jpeg");
            imagejpeg($src);
            imagedestroy($src);
        }
    }

    private function createImage($path) {
        switch (exif_imagetype($path)) {
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($path);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($path);
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($path);
                break;
            default:
                $image = NULL;
        }

        return $image;
    }

}
