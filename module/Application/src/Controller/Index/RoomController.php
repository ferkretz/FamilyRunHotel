<?php

namespace Application\Controller\Index;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Service\Option\SettingsManager;
use Application\Service\Room\RoomManager;
use Application\Service\Room\RoomQueryManager;
use Application\Service\User\AccessManager;

class RoomController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Room entity manager.
     * @var RoomManager
     */
    protected $roomManager;

    /**
     * Room query manager.
     * @var RoomQueryManager
     */
    protected $roomQueryManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(SettingsManager $settingsManager,
                                RoomManager $roomManager,
                                RoomQueryManager $roomQueryManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->roomManager = $roomManager;
        $this->roomQueryManager = $roomQueryManager;
        $this->accessManager = $accessManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('list.offers');

        $page = $this->params()->fromQuery('page', 1);
        $this->roomQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->roomQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $this->roomQueryManager->setLocale($this->settingsManager->getSetting(SettingsManager::LOCALE));
        $roomQuery = $this->roomQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomQuery, false));
        $rooms = new Paginator($adapter);
        $rooms->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $rooms->setCurrentPageNumber($page);

        return new ViewModel([
            'rooms' => $rooms,
            'roomQueryManager' => $this->roomQueryManager,
            'settingsManager' => $this->settingsManager,
        ]);
    }

    public function viewAction() {
        $this->accessManager->currentUserRedirect('view.offers');

        $id = $this->params()->fromRoute('id', -1);
        $room = $this->roomManager->findOneById($id);
        if ($room == null) {
            return $this->notFoundAction();
        }
        $locale = $this->settingsManager->getSetting(SettingsManager::LOCALE);
        if (!$room->getTranslations()->containsKey($locale)) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'room' => $room,
            'locale' => $locale,
            'settingsManager' => $this->settingsManager,
        ]);
    }

}
