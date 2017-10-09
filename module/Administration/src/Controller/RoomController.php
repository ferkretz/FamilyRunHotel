<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Entity\Room;
use Administration\Form\RoomAddForm;
use Administration\Form\RoomEditForm;
use Administration\Form\RoomIndexForm;
use Administration\Service\RoomQueryManager;
use Administration\Service\RoomManager;
use Application\Service\Localizator;

class RoomController extends AbstractActionController {

    /**
     * RoomQuery manager.
     * @var RoomQueryManager
     */
    protected $roomQueryManager;

    /**
     * Room manager.
     * @var RoomManager
     */
    protected $roomManager;
    protected $localizator;

    public function __construct(RoomQueryManager $roomQueryManager,
                                RoomManager $roomManager,
                                Localizator $localizator) {
        $this->roomQueryManager = $roomQueryManager;
        $this->roomManager = $roomManager;
        $this->localizator = $localizator;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $orderBy = $this->params()->fromQuery('orderBy', RoomQueryManager::ORDER_BY_ID);
        $order = $this->params()->fromQuery('order', RoomQueryManager::ORDER_ASC);

        $this->roomQueryManager->setOrder($order);
        $this->roomQueryManager->setOrderBy($orderBy);
        $roomQuery = $this->roomQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        foreach ($paginator as $room) {
            $roomIds[] = $room->getId();

            $translationArr = [];
            foreach ($room->getTranslations() as $translation) {
                $translationArr[] = \Locale::getDisplayName($translation->getLocale());
            }
            $translationList[$room->getId()] = implode(', ', $translationArr);
        }
        $form = new RoomIndexForm($roomIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (count($data)) {
                    foreach ($data['rooms'] as $index) {
                        $rooms[] = $paginator->getItem($index + 1);
                    }
                    foreach ($rooms as $room) {
                        $this->roomManager->remove($room);
                    }
                } else {
                    throw new \Exception('There are no rooms to delete.');
                }

                return $this->redirect()->toRoute('admin-rooms');
            } else {
                throw new \Exception(current($form->getMessages()['rooms'][0]));
            }
        }

        return new ViewModel([
            'form' => $form,
            'rooms' => $paginator,
            'translationList' => $translationList,
            'orderBy' => $this->roomQueryManager->getOrderBy(),
            'order' => $this->roomQueryManager->getOrder(),
            'requiredQuery' => ['orderBy' => $this->roomQueryManager->getOrderBy(), 'order' => $this->roomQueryManager->getOrder()],
        ]);
    }

    public function editAction() {
        $localeInfo = localeconv();
        $locales = $this->localizator->getSupportedLocales();
        $form = new RoomEditForm($locales);

        $id = $this->params()->fromRoute('id', -1);
        $translation = $this->params()->fromRoute('translation', 'none');
        $room = $this->roomManager->findById($id);
        if ($room == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($translation != 'none') {
                $form->getInputFilter()->get('translationSummary')->setRequired(TRUE);
            }

            if ($form->isValid()) {
                $data['price'] = str_replace($localeInfo['decimal_point'], '.', $data['price']);
                $room->setData($data);
                $this->roomManager->update();
            }
        } else {
            $data = $room->getData($translation);
            $data['price'] = number_format($data['price'], 2, $localeInfo['decimal_point'], '');
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'translation' => $translation,
            'translationName' => \Locale::getDisplayName($translation),
            'fallbackLocaleName' => current($this->localizator->getFallbackLocale()),
            'form' => $form,
        ]);
    }

    public function addAction() {
        $form = new RoomAddForm();

        $room = new Room();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $room->setData($data);
                $this->roomManager->add($room);

                return $this->redirect()->toRoute('admin-rooms');
            }
        }

        return new ViewModel([
            'fallbackLocaleName' => current($this->localizator->getFallbackLocale()),
            'form' => $form,
        ]);
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
