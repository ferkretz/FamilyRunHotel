<?php

namespace Home\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Service\Locale\CurrentLocaleEntityManager;
use Application\Service\Picture\PictureEntityManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Room\RoomQueryManager;
use Application\Service\Site\SiteOptionValueManager;

class RoomController extends AbstractActionController {

    /**
     * Room entity manager.
     * @var RoomEntityManager
     */
    protected $roomEntityManager;

    /**
     * Current locale entity manager.
     * @var CurrentLocaleEntityManager
     */
    protected $currentLocaleEntityManager;

    /**
     * Site option value manager
     * @var SiteOptionValueManager
     */
    protected $siteOptionValueManager;

    /**
     * Picture entity manager.
     * @var PictureEntityManager
     */
    protected $pictureEntityManager;

    public function __construct(RoomEntityManager $roomEntityManager,
                                CurrentLocaleEntityManager $currentLocaleEntityManager,
                                SiteOptionValueManager $siteOptionValueManager,
                                PictureEntityManager $pictureEntityManager) {
        $this->roomEntityManager = $roomEntityManager;
        $this->currentLocaleEntityManager = $currentLocaleEntityManager;
        $this->siteOptionValueManager = $siteOptionValueManager;
        $this->pictureEntityManager = $pictureEntityManager;
    }

    public function indexAction() {
        $roomQueryManager = $this->queryManager(RoomQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $roomQueryManager->setOrder($this->params()->fromQuery('order'));
        $roomQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $roomQueryManager->setLocaleId($this->currentLocaleEntityManager->get()->getId());
        $roomQuery = $roomQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $this->layout()->activeMenuItemId = 'homeRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'rooms' => $paginator,
            'roomQueryManager' => $roomQueryManager,
            'currency' => $company['currency'],
        ]);
    }

    public function viewAction() {
        $id = $this->params()->fromRoute('id', -1);
        $roomEntity = $this->roomEntityManager->findOneById($id);
        $localeId = $this->currentLocaleEntityManager->get()->getId();
        if ($roomEntity == NULL || !$roomEntity->getTranslations()->containsKey($localeId)) {
            return $this->notFoundAction();
        }

        $this->layout()->activeMenuItemId = 'homeRoom';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'localeId' => $this->localeEntityManager->getCurrent()->getId(),
            'room' => $roomEntity,
            'currency' => $company['currency'],
        ]);
    }

    public function getPictureAction() {
        $id = $this->params()->fromRoute('id', -1);
        $pictureEntity = $this->pictureEntityManager->findOneById($id);

        header("Content-type:  image/jpeg");
        echo stream_get_contents($pictureEntity->getContent());
    }

}
