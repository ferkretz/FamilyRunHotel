<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Entity\Picture;
use Administration\Entity\PictureTranslation;
use Administration\Form\PictureAddForm;
use Administration\Form\PictureEditForm;
use Administration\Form\PictureIndexForm;
use Administration\Service\PictureQueryManager;
use Administration\Service\PictureManager;
use Application\Service\Localizator;

class PictureController extends AbstractActionController {

    /**
     * PictureQuery manager.
     * @var PictureQueryManager
     */
    protected $pictureQueryManager;

    /**
     * Picture manager.
     * @var PictureManager
     */
    protected $pictureManager;
    protected $localizator;

    public function __construct(PictureQueryManager $pictureQueryManager,
                                PictureManager $pictureManager,
                                Localizator $localizator) {
        $this->pictureQueryManager = $pictureQueryManager;
        $this->pictureManager = $pictureManager;
        $this->localizator = $localizator;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $orderBy = $this->params()->fromQuery('orderBy', PictureQueryManager::ORDER_BY_ID);
        $order = $this->params()->fromQuery('order', PictureQueryManager::ORDER_ASC);

        $this->pictureQueryManager->setOrder($order);
        $this->pictureQueryManager->setOrderBy($orderBy);
        $pictureQuery = $this->pictureQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($pictureQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        $pictureIds = [];
        $translationList = [];
        foreach ($paginator as $picture) {
            $pictureIds[] = $picture->getId();

            $translationArr = [];
            foreach ($picture->getTranslations() as $translation) {
                $translationArr[] = \Locale::getDisplayName($translation->getLocale());
            }
            $translationList[$picture->getId()] = implode(', ', $translationArr);
        }
        $form = new PictureIndexForm($pictureIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (count($data)) {
                    foreach ($data['pictures'] as $index) {
                        $pictures[] = $paginator->getItem($index + 1);
                    }
                    foreach ($pictures as $picture) {
                        $this->pictureManager->remove($picture);
                    }
                } else {
                    throw new \Exception('There are no pictures to delete.');
                }

                return $this->redirect()->toRoute('admin-pictures');
            } else {
                throw new \Exception(current($form->getMessages()['pictures'][0]));
            }
        }

        return new ViewModel([
            'form' => $form,
            'pictures' => $paginator,
            'translationList' => $translationList,
            'orderBy' => $this->pictureQueryManager->getOrderBy(),
            'order' => $this->pictureQueryManager->getOrder(),
            'requiredQuery' => ['orderBy' => $this->pictureQueryManager->getOrderBy(), 'order' => $this->pictureQueryManager->getOrder()],
        ]);
    }

    public function editAction() {
        $form = new PictureEditForm($this->localizator->getSupportedLocaleNames());

        $id = $this->params()->fromRoute('id', -1);
        $translationLocale = $this->params()->fromRoute('translationLocale', 'none');
        $supportedLocales = array_merge(['none'], $this->localizator->getSupportedLocales());
        $picture = $this->pictureManager->findById($id);
        if ($picture == NULL || !in_array($translationLocale, $supportedLocales)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($translationLocale != 'none') {
                $form->getInputFilter()->get('translationSummary')->setRequired(TRUE);
            }

            if ($form->isValid()) {
                $picture->setSummary($data['summary']);
                $picture->setLicense($data['license']);
                if ($translationLocale != 'none') {
                    $translation = new PictureTranslation();
                    $translation->setSummary($data['summary']);
                    $picture->setTranslation($translationLocale, $translation);
                }
                $this->pictureManager->update();
            }
        } else {
            $data['summary'] = $picture->getSummary();
            $data['license'] = $picture->getLicense();
            if ($translationLocale != 'none') {
                $translation = $picture->getTranslation($translationLocale);
                if ($translation) {
                    $data['translationSummary'] = $translation->getSummary();
                }
            }
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'translationLocale' => $translationLocale,
            'translationName' => \Locale::getDisplayName($translationLocale),
            'fallbackLocaleName' => \Locale::getDisplayName($this->localizator->getFallbackLocale()),
            'form' => $form,
        ]);
    }

    public function addAction() {
        $form = new PictureAddForm();
        $picture = new Picture();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $picture->setSummary($data['summary']);
                $picture->setLicense($data['license']);
                $picture->setUploaded(new \DateTime(NULL, new \DateTimeZone("UTC")));
                $this->pictureManager->add($picture);

                return $this->redirect()->toRoute('admin-pictures');
            }
        }

        return new ViewModel([
            'fallbackLocaleName' => \Locale::getDisplayName($this->localizator->getFallbackLocale()),
            'form' => $form,
        ]);
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
