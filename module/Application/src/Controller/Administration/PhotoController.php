<?php

namespace Application\Controller\Administration;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Photo\AddForm;
use Application\Form\Administration\Photo\ListForm;
use Application\Entity\Photo\Photo;
use Application\Service\Option\SettingsManager;
use Application\Service\Photo\PhotoManager;
use Application\Service\Photo\PhotoQueryManager;
use Application\Service\User\AccessManager;

class PhotoController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Photo entity manager.
     * @var PhotoManager
     */
    protected $photoManager;

    /**
     * Photo query manager.
     * @var PhotoQueryManager
     */
    protected $photoQueryManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(SettingsManager $settingsManager,
                                PhotoManager $photoManager,
                                PhotoQueryManager $photoQueryManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->photoManager = $photoManager;
        $this->photoQueryManager = $photoQueryManager;
        $this->accessManager = $accessManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('list.offers');

        $page = $this->params()->fromQuery('page', 1);
        $this->photoQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->photoQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $photoQuery = $this->photoQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($photoQuery, false));
        $photos = new Paginator($adapter);
        $photos->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::PHOTOS_PER_PAGE));
        $photos->setCurrentPageNumber($page);

        $photoIds = [];
        foreach ($photos as $photo) {
            $photoIds[$photo->getId()] = $photo->getId();
        }
        $form = new ListForm($photoIds);

        if ($this->getRequest()->isPost()) {
            $this->accessManager->currentUserTry('delete.offers');

            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['photos'] as $photoId) {
                        $this->photoManager->removeById($photoId);
                    }
                } else {
                    throw new \Exception('There are no photos to delete.');
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'photos' => $photos,
            'photoQueryManager' => $this->photoQueryManager,
            'settingsManager' => $this->settingsManager,
            'accessManager' => $this->accessManager
        ]);
    }

    public function addAction() {
        $this->accessManager->currentUserRedirect('add.offers');

        $form = new AddForm($this->settingsManager);

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                foreach ($data['file'] as $file) {
                    if (!empty($file['tmp_name'])) {
                        $image = $this->createImage($file['tmp_name']);
                        if ($image) {
                            $photo = new Photo();
                            $photo->setUploaded(new \DateTime('now', new \DateTimeZone('UTC')));
                            ob_start();
                            imagejpeg($image, NULL, $this->settingsManager->getSetting(SettingsManager::JPEG_QUALITY));
                            $photo->setContent(ob_get_contents());
                            ob_get_clean();
                            imagedestroy($image);
                            $this->photoManager->insert($photo);
                        }
                    }
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function viewAction() {
        $this->accessManager->currentUserRedirect('view.offers');

        $id = $this->params()->fromRoute('id', -1);
        return new ViewModel([
            'id' => $id,
        ]);
    }

    protected function createImage($path) {
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
