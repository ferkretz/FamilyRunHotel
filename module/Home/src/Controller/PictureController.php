<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\Picture\AddForm;
use Administration\Form\Picture\IndexForm;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Picture\PictureQueryManager;

class PictureController extends AbstractActionController {

    /**
     * Picture entity manager.
     * @var PictureEntityManager
     */
    protected $pictureEntityManager;

    /**
     * Upload options
     * @var array
     */
    protected $uploadOptions;

    public function __construct(PictureEntityManager $pictureEntityManager,
                                $uploadOptions) {
        $this->pictureEntityManager = $pictureEntityManager;
        $this->uploadOptions = $uploadOptions;
        $this->uploadOptions['pictureEntityManager'] = $pictureEntityManager;
    }

    public function indexAction() {
        $pictureQueryManager = $this->queryManager(PictureQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $pictureQueryManager->setOrder($this->params()->fromQuery('order'));
        $pictureQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $pictureQuery = $pictureQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($pictureQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $pictureIds = [];
        foreach ($paginator as $pictureEntity) {
            $pictureIds[$pictureEntity->getId()] = $pictureEntity->getId();
        }
        $form = new IndexForm($pictureIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['pictures'] as $pictureId) {
                        $this->pictureEntityManager->removeById($pictureId);
                    }
                } else {
                    throw new \Exception('There are no pictures to delete.');
                }

                return $this->redirect()->toRoute(NULL);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationPicture';

        return new ViewModel([
            'form' => $form,
            'thumbnailWidth' => $this->uploadOptions['thumbnailWidth'],
            'pictures' => $paginator,
            'pictureQueryManager' => $pictureQueryManager,
        ]);
    }

    public function thumbnailAction() {
        $id = $this->params()->fromRoute('id', -1);
        $pictureEntity = $this->pictureEntityManager->findOneById($id);

        $src = imagecreatefromstring(stream_get_contents($pictureEntity->getContent()));
        $srcWidth = imagesx($src);
        $srcHeight = imagesy($src);
        $width = $this->uploadOptions['thumbnailWidth'];
        $height = $srcHeight / $srcWidth * $width;
        $dst = imagecreatetruecolor($width, $height);
        imagecopyresized($dst, $src, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

        header("Content-type:  image/jpeg");
        imagejpeg($dst);
        imagedestroy($dst);
    }

    public function addAction() {
        $form = new AddForm($this->uploadOptions);

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
            }

            return $this->redirect()->toRoute(NULL);
        }

        $this->layout()->activeMenuItemId = 'administrationPicture';

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
