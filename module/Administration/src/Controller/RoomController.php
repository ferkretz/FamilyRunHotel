<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\Room\AddForm;
use Administration\Form\Room\AvailablePicturesForm;
use Administration\Form\Room\AvailableServicesForm;
use Administration\Form\Room\EditForm;
use Administration\Form\Room\IndexForm;
use Administration\Form\Room\SelectedPicturesForm;
use Administration\Form\Room\SelectedServicesForm;
use Application\Entity\Room\RoomEntity;
use Application\Entity\Room\RoomTranslationEntity;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Picture\PictureQueryManager;
use Application\Service\Service\ServiceEntityManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Room\RoomQueryManager;

class RoomController extends AbstractActionController {

    /**
     * Room entity manager.
     * @var RoomEntityManager
     */
    protected $roomEntityManager;

    /**
     * Locale entity manager.
     * @var LocaleEntityManager
     */
    protected $localeEntityManager;

    /**
     * Site option value manager
     * @var SiteOptionValueManager
     */
    protected $siteOptionValueManager;

    /**
     * Service entity manager.
     * @var ServiceEntityManager
     */
    protected $serviceEntityManager;

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

    public function __construct(RoomEntityManager $roomEntityManager,
                                LocaleEntityManager $localeEntityManager,
                                SiteOptionValueManager $siteOptionValueManager,
                                ServiceEntityManager $serviceEntityManager,
                                PictureEntityManager $pictureEntityManager,
                                $uploadOptions) {
        $this->roomEntityManager = $roomEntityManager;
        $this->localeEntityManager = $localeEntityManager;
        $this->siteOptionValueManager = $siteOptionValueManager;
        $this->serviceEntityManager = $serviceEntityManager;
        $this->pictureEntityManager = $pictureEntityManager;
        $this->uploadOptions = $uploadOptions;
        $this->uploadOptions['pictureEntityManager'] = $pictureEntityManager;
    }

    public function indexAction() {
        $roomQueryManager = $this->queryManager(RoomQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $roomQueryManager->setOrder($this->params()->fromQuery('order'));
        $roomQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $roomQueryManager->setLocaleId($this->params()->fromQuery('localeId'), $this->localeEntityManager->findAllId());
        $roomQuery = $roomQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $roomIds = [];
        foreach ($paginator as $roomEntity) {
            $roomIds[$roomEntity->getId()] = $roomEntity->getId();
        }
        $form = new IndexForm($roomIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['rooms'] as $roomId) {
                        $this->roomEntityManager->removeById($roomId);
                    }
                } else {
                    throw new \Exception('There are no rooms to delete.');
                }

                return $this->redirect()->toRoute(NULL);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'form' => $form,
            'rooms' => $paginator,
            'locales' => $this->localeEntityManager->findAllDisplayName(),
            'roomQueryManager' => $roomQueryManager,
            'currency' => $company['currency'],
        ]);
    }

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL) {
            return $this->notFoundAction();
        }
        $locales = $this->localeEntityManager->findAllDisplayName();
        $localeId = $this->params()->fromQuery('localeId');
        if (!array_key_exists($localeId, $locales)) {
            $localeId = 1;
        }

        $form = new EditForm();

        $decimalFormatter = new \NumberFormatter(NULL, \NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {
                $roomEntity->setPrice($decimalFormatter->parse($data['price']));
                if ($roomEntity->getTranslations()->containsKey($localeId)) {
                    $translationEntity = $roomEntity->getTranslations()->get($localeId);
                    $translationEntity->setSummary($data['summary']);
                    $translationEntity->setDescription($data['description']);
                    $this->roomEntityManager->update();
                } else {
                    $translationEntity = new RoomTranslationEntity();
                    $translationEntity->setRoom($roomEntity);
                    $translationEntity->setLocale($this->localeEntityManager->findOneById($localeId));
                    $translationEntity->setSummary($data['summary']);
                    $translationEntity->setDescription($data['description']);
                    $roomEntity->getTranslations()->add($translationEntity);
                    $this->roomEntityManager->insert($roomEntity);
                }
            }
        } else {
            $data['price'] = $decimalFormatter->format($roomEntity->getPrice());
            if ($roomEntity->getTranslations()->containsKey($localeId)) {
                $translationEntity = $roomEntity->getTranslations()->get($localeId);
                $data['summary'] = $translationEntity->getSummary();
                $data['description'] = $translationEntity->getDescription();
            }
            $form->setData($data);
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'id' => $id,
            'localeId' => $localeId,
            'locales' => $locales,
            'form' => $form,
            'currency' => $company['currency'],
        ]);
    }

    public function addAction() {
        $form = new AddForm();

        $decimalFormatter = new \NumberFormatter(NULL, \NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $roomEntity = new RoomEntity();
                $roomEntity->setPrice($decimalFormatter->parse($data['price']));
                $translationEntity = new RoomTranslationEntity();
                $translationEntity->setRoom($roomEntity);
                $translationEntity->setLocale($this->localeEntityManager->findOneById(1));
                $translationEntity->setSummary($data['summary']);
                $translationEntity->setDescription($data['description']);
                $roomEntity->getTranslations()->add($translationEntity);
                $this->roomEntityManager->insert($roomEntity);

                return $this->redirect()->toRoute(NULL, ['action' => 'edit', 'id' => $roomEntity->getId()], ['query' => ['localeId' => 1]]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'defaultLocaleDisplayName' => \Locale::getDisplayName($this->localeEntityManager->findOneById(1)->getName()),
            'form' => $form,
            'currency' => $company['currency'],
        ]);
    }

    public function availableServicesAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL) {
            return $this->notFoundAction();
        }
        $serviceQueryManager = $this->queryManager(ServiceQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $serviceQueryManager->setOrder($this->params()->fromQuery('order'));
        $serviceQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $serviceQueryManager->setLocaleId($this->params()->fromQuery('localeId'), $this->localeEntityManager->findAllId());
        $serviceQueryManager->setRoomId($id, TRUE);
        $serviceQuery = $serviceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($serviceQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $serviceIds = [];
        foreach ($paginator as $serviceEntity) {
            $serviceIds[$serviceEntity->getId()] = $serviceEntity->getId();
        }
        $form = new AvailableServicesForm($serviceIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $serviceCollection = $roomEntity->getServices();
                    foreach ($data['services'] as $serviceId) {
                        if (!$serviceCollection->containsKey($serviceId)) {
                            $serviceCollection->add($this->serviceEntityManager->findOneById($serviceId));
                        }
                    }
                    $this->roomEntityManager->update();
                } else {
                    throw new \Exception('There are no services to add.');
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'availableServices', 'id' => $id]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'id' => $id,
            'form' => $form,
            'services' => $paginator,
            'locales' => $this->localeEntityManager->findAllDisplayName(),
            'serviceQueryManager' => $serviceQueryManager,
            'currency' => $company['currency'],
        ]);
    }

    public function selectedServicesAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL) {
            return $this->notFoundAction();
        }
        $serviceQueryManager = $this->queryManager(ServiceQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $serviceQueryManager->setOrder($this->params()->fromQuery('order'));
        $serviceQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $serviceQueryManager->setLocaleId($this->params()->fromQuery('localeId'), $this->localeEntityManager->findAllId());
        $serviceQueryManager->setRoomId($id);
        $serviceQuery = $serviceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($serviceQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $serviceIds = [];
        foreach ($paginator as $serviceEntity) {
            $serviceIds[$serviceEntity->getId()] = $serviceEntity->getId();
        }
        $form = new SelectedServicesForm($serviceIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $serviceCollection = $roomEntity->getServices();
                    foreach ($data['services'] as $serviceId) {
                        if ($serviceCollection->containsKey($serviceId)) {
                            $serviceCollection->remove($serviceId);
                        }
                    }
                    $this->roomEntityManager->update();
                } else {
                    throw new \Exception('There are no services to delete.');
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'selectedServices', 'id' => $id]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'id' => $id,
            'form' => $form,
            'services' => $paginator,
            'locales' => $this->localeEntityManager->findAllDisplayName(),
            'serviceQueryManager' => $serviceQueryManager,
            'currency' => $company['currency'],
        ]);
    }

    public function availablePicturesAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL) {
            return $this->notFoundAction();
        }
        $pictureQueryManager = $this->queryManager(PictureQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $pictureQueryManager->setOrder($this->params()->fromQuery('order'));
        $pictureQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $pictureQueryManager->setRoomId($id, TRUE);
        $pictureQuery = $pictureQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($pictureQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $pictureIds = [];
        foreach ($paginator as $pictureEntity) {
            $pictureIds[$pictureEntity->getId()] = $pictureEntity->getId();
        }
        $form = new AvailablePicturesForm($pictureIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $pictureCollection = $roomEntity->getPictures();
                    foreach ($data['pictures'] as $pictureId) {
                        if (!$pictureCollection->containsKey($pictureId)) {
                            $pictureCollection->add($this->pictureEntityManager->findOneById($pictureId));
                        }
                    }
                    $this->roomEntityManager->update();
                } else {
                    throw new \Exception('There are no pictures to add.');
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'availablePictures', 'id' => $id]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        return new ViewModel([
            'id' => $id,
            'form' => $form,
            'thumbnailWidth' => $this->uploadOptions['thumbnailWidth'],
            'pictures' => $paginator,
            'pictureQueryManager' => $pictureQueryManager,
        ]);
    }

    public function selectedPicturesAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL) {
            return $this->notFoundAction();
        }
        $pictureQueryManager = $this->queryManager(PictureQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $pictureQueryManager->setOrder($this->params()->fromQuery('order'));
        $pictureQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $pictureQueryManager->setRoomId($id);
        $pictureQuery = $pictureQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($pictureQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        $pictureIds = [];
        foreach ($paginator as $pictureEntity) {
            $pictureIds[$pictureEntity->getId()] = $pictureEntity->getId();
        }
        $form = new SelectedPicturesForm($pictureIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $pictureCollection = $roomEntity->getPictures();
                    foreach ($data['pictures'] as $pictureId) {
                        if ($pictureCollection->containsKey($pictureId)) {
                            $pictureCollection->remove($pictureId);
                        }
                    }
                    $this->roomEntityManager->update();
                } else {
                    throw new \Exception('There are no pictures to delete.');
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'selectedPictures', 'id' => $id]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationRoom';

        return new ViewModel([
            'id' => $id,
            'form' => $form,
            'thumbnailWidth' => $this->uploadOptions['thumbnailWidth'],
            'pictures' => $paginator,
            'pictureQueryManager' => $pictureQueryManager,
        ]);
    }

}
