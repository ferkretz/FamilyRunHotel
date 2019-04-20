<?php

namespace Application\Controller\Administration;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Room\AddForm;
use Application\Form\Administration\Room\EditForm;
use Application\Form\Administration\Room\EditPhotosForm;
use Application\Form\Administration\Room\EditServicesForm;
use Application\Form\Administration\Room\ListForm;
use Application\Entity\Room\Room;
use Application\Entity\Room\RoomTranslation;
use Application\Service\Option\SettingsManager;
use Application\Service\Photo\PhotoManager;
use Application\Service\Photo\PhotoQueryManager;
use Application\Service\Room\RoomManager;
use Application\Service\Room\RoomQueryManager;
use Application\Service\Service\ServiceManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\User\AccessManager;

class RoomController extends AbstractActionController {

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
     * Service entity manager.
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Service query manager.
     * @var ServiceQueryManager
     */
    protected $serviceQueryManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(SettingsManager $settingsManager,
                                PhotoManager $photoManager,
                                PhotoQueryManager $photoQueryManager,
                                RoomManager $roomManager,
                                RoomQueryManager $roomQueryManager,
                                ServiceManager $serviceManager,
                                ServiceQueryManager $serviceQueryManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->photoManager = $photoManager;
        $this->photoQueryManager = $photoQueryManager;
        $this->roomManager = $roomManager;
        $this->roomQueryManager = $roomQueryManager;
        $this->serviceManager = $serviceManager;
        $this->serviceQueryManager = $serviceQueryManager;
        $this->accessManager = $accessManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('list.offers');

        $page = $this->params()->fromQuery('page', 1);
        $this->roomQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->roomQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!in_array($locale, $locales)) {
            return $this->notFoundAction();
        }
        $this->roomQueryManager->setLocale($locale);
        $roomQuery = $this->roomQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomQuery, false));
        $rooms = new Paginator($adapter);
        $rooms->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $rooms->setCurrentPageNumber($page);

        $roomIds = [];
        foreach ($rooms as $room) {
            $roomIds[$room->getId()] = $room->getId();
        }
        $form = new ListForm($roomIds);

        if ($this->getRequest()->isPost()) {
            $this->accessManager->currentUserTry('delete.offers');

            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['rooms'] as $roomId) {
                        $this->roomManager->removeById($roomId);
                    }
                } else {
                    throw new \Exception('There are no rooms to delete.');
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'rooms' => $rooms,
            'roomQueryManager' => $this->roomQueryManager,
            'settingsManager' => $this->settingsManager,
            'accessManager' => $this->accessManager
        ]);
    }

    public function editAction() {
        $this->accessManager->currentUserRedirect('edit.offers');

        $id = $this->params()->fromRoute('id', -1);
        $room = $this->roomManager->findOneById($id);
        if (!$room) {
            return $this->notFoundAction();
        }
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!(in_array($locale, $locales) && $room->getTranslations()->containsKey($this->settingsManager->getSetting(SettingsManager::LOCALE, true)))) {
            return $this->notFoundAction();
        }

        $form = new EditForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {
                $room->setPrice($data['price']);
                if ($room->getTranslations()->containsKey($locale)) {
                    $translation = $room->getTranslations()->get($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $this->roomManager->update();
                } else {
                    $translation = new RoomTranslation();
                    $translation->setRoom($room);
                    $translation->setLocale($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $room->getTranslations()->add($translation);
                    $this->roomManager->insert($room);
                }
            }
        } else {
            $data['price'] = $room->getPrice();
            if ($room->getTranslations()->containsKey($locale)) {
                $translation = $room->getTranslations()->get($locale);
                $data['summary'] = $translation->getSummary();
                $data['description'] = $translation->getDescription();
            }
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'placeholder' => $room->getTranslations()->get($this->settingsManager->getSetting(SettingsManager::LOCALE, true)),
            'locale' => $locale,
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function addAction() {
        $this->accessManager->currentUserRedirect('add.offers');

        $form = new AddForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $room = new Room();
                $room->setPrice($data['price']);
                $translation = new RoomTranslation();
                $translation->setRoom($room);
                $translation->setLocale($this->settingsManager->getSetting(SettingsManager::LOCALE, true));
                $translation->setSummary($data['summary']);
                $translation->setDescription($data['description']);
                $room->getTranslations()->add($translation);
                $this->roomManager->insert($room);

                return $this->redirect()->toRoute(null, ['action' => 'edit', 'id' => $room->getId()], ['query' => ['locale' => $this->settingsManager->getSetting(SettingsManager::LOCALE, true)]]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function editServicesAction() {
        $this->accessManager->currentUserRedirect('edit.offers');

        $id = $this->params()->fromRoute('id', -1);
        $room = $this->roomManager->findOneById($id);
        if ($room == null) {
            return $this->notFoundAction();
        }
        $page = $this->params()->fromQuery('page', 1);
        $this->serviceQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->serviceQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!in_array($locale, $locales)) {
            return $this->notFoundAction();
        }
        $this->serviceQueryManager->setLocale($locale);
        $this->serviceQueryManager->setRoomId($id);
        $action = $this->params()->fromQuery('action', 'add');
        $this->serviceQueryManager->setInverseRoomSelect($action == 'add' ? true : false);
        $serviceQuery = $this->serviceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($serviceQuery, false));
        $services = new Paginator($adapter);
        $services->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $services->setCurrentPageNumber($page);

        $serviceIds = [];
        foreach ($services as $service) {
            $serviceIds[$service->getId()] = $service->getId();
        }
        $form = new EditServicesForm($serviceIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $serviceCollection = $room->getServices();
                    foreach ($data['services'] as $serviceId) {
                        if (($action == 'add') && (!$serviceCollection->containsKey($serviceId))) {
                            $serviceCollection->add($this->serviceManager->findOneById($serviceId));
                        } else {
                            $serviceCollection->remove($serviceId);
                        }
                    }
                    $this->roomManager->update();
                } else {
                    throw new \Exception($action == 'add' ? 'There are no services to add.' : 'There are no services to delete.');
                }

                return $this->redirect()->toRoute(null, ['action' => 'editServices', 'id' => $room->getId()], ['query' => array_merge($this->serviceQueryManager->getParams(), ['action' => $action])]);
            }
        }

        return new ViewModel([
            'action' => $action,
            'roomId' => $room->getId(),
            'form' => $form,
            'locale' => $locale,
            'services' => $services,
            'serviceQueryManager' => $this->serviceQueryManager,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function editPhotosAction() {
        $this->accessManager->currentUserRedirect('edit.offers');

        $id = $this->params()->fromRoute('id', -1);
        $room = $this->roomManager->findOneById($id);
        if ($room == null) {
            return $this->notFoundAction();
        }
        $page = $this->params()->fromQuery('page', 1);
        $this->photoQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->photoQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $this->photoQueryManager->setRoomId($id);
        $action = $this->params()->fromQuery('action', 'add');
        $this->photoQueryManager->setInverseRoomSelect($action == 'add' ? true : false);
        $photoQuery = $this->photoQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($photoQuery, false));
        $photos = new Paginator($adapter);
        $photos->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::PHOTOS_PER_PAGE));
        $photos->setCurrentPageNumber($page);

        $photoIds = [];
        foreach ($photos as $photo) {
            $photoIds[$photo->getId()] = $photo->getId();
        }
        $form = new EditPhotosForm($photoIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $photoCollection = $room->getPhotos();
                    foreach ($data['photos'] as $photoId) {
                        if (($action == 'add') && (!$photoCollection->containsKey($photoId))) {
                            $photoCollection->add($this->photoManager->findOneById($photoId));
                        } else {
                            $photoCollection->remove($photoId);
                        }
                    }
                    $this->roomManager->update();
                } else {
                    throw new \Exception($action == 'add' ? 'There are no photos to add.' : 'There are no photos to delete.');
                }

                return $this->redirect()->toRoute(null, ['action' => 'editPhotos', 'id' => $room->getId()], ['query' => array_merge($this->photoQueryManager->getParams(), ['action' => $action])]);
            }
        }

        return new ViewModel([
            'action' => $action,
            'roomId' => $room->getId(),
            'form' => $form,
            'photos' => $photos,
            'photoQueryManager' => $this->photoQueryManager,
            'settingsManager' => $this->settingsManager
        ]);
    }

}
