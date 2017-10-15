<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\RoomAddForm;
use Administration\Form\RoomEditForm;
use Administration\Form\RoomIndexForm;
use Administration\Service\RoomQueryManager;
use Application\Entity\Room;
use Application\Entity\RoomTranslation;
use Application\Service\Localizator;
use Application\Service\RoomManager;
use Application\Service\SiteOptionManager;

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

    /**
     * Picture manager.
     * @var OptionManager
     */
    protected $optionManager;

    public function __construct(RoomQueryManager $roomQueryManager,
                                RoomManager $roomManager,
                                Localizator $localizator,
                                SiteOptionManager $optionManager) {
        $this->roomQueryManager = $roomQueryManager;
        $this->roomManager = $roomManager;
        $this->localizator = $localizator;
        $this->optionManager = $optionManager;
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

        $roomIds = [];
        $translationList = [];
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

                return $this->redirect()->toRoute('administrationRoom');
            } else {
                throw new \Exception(current($form->getMessages()['rooms'][0]));
            }
        }

        $this->layout()->navBarData->setActiveItemId('administrationRoom');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
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
        $form = new RoomEditForm($this->localizator->getSupportedLocaleNames());

        $id = $this->params()->fromRoute('id', -1);
        $translationLocale = $this->params()->fromRoute('translationLocale', 'none');
        $supportedLocales = array_merge(['none'], $this->localizator->getSupportedLocales());
        $room = $this->roomManager->findById($id);
        if ($room == NULL || !in_array($translationLocale, $supportedLocales)) {
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
                $room->setSummary($data['summary']);
                $room->setPrice(str_replace(localeconv()['decimal_point'], '.', $data['price']));
                $room->setCurrency($data['currency']);
                $room->setDescription($data['description']);
                if ($translationLocale != 'none') {
                    $translation = new RoomTranslation();
                    $translation->setSummary($data['translationSummary']);
                    $translation->setDescription($data['translationDescription']);
                    $room->setTranslation($translationLocale, $translation);
                }
                $this->roomManager->update();
            }
        } else {
            $data['summary'] = $room->getSummary();
            $data['price'] = number_format($room->getPrice(), 2, localeconv()['decimal_point'], '');
            $data['currency'] = $room->getCurrency();
            $data['description'] = $room->getDescription();
            if ($translationLocale != 'none') {
                $translation = $room->getTranslation($translationLocale);
                if ($translation) {
                    $data['translationSummary'] = $translation->getSummary();
                    $data['translationDescription'] = $translation->getDescription();
                }
            }
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('administrationRoom');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
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
        $form = new RoomAddForm();
        $room = new Room();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $room->setSummary($data['summary']);
                $room->setPrice(str_replace(localeconv()['decimal_point'], '.', $data['price']));
                $room->setCurrency($data['currency']);
                $room->setDescription($data['description']);
                $this->roomManager->add($room);

                return $this->redirect()->toRoute('administrationRoom', ['action' => 'edit', 'id' => $room->getId()]);
            }
        }

        $this->layout()->navBarData->setActiveItemId('administrationRoom');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
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
