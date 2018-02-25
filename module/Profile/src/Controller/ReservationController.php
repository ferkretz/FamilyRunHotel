<?php

namespace Profile\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Service\Locale\CurrentLocaleEntityManager;
use Application\Service\Reservation\ReservationEntityManager;
use Application\Service\Reservation\ReservationQueryManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\User\CurrentUserEntityManager;

class ReservationController extends AbstractActionController {

    /**
     * Reservation entity manager.
     * @var ReservationEntityManager
     */
    protected $reservationEntityManager;

    /**
     * Room entity manager.
     * @var RoomEntityManager
     */
    protected $roomEntityManager;

    /**
     * Current user entity manager.
     * @var CurrentUserEntityManager
     */
    protected $currentUserEntityManager;

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

    public function __construct(ReservationEntityManager $reservationEntityManager,
                                RoomEntityManager $roomEntityManager,
                                CurrentUserEntityManager $currentUserEntityManager,
                                CurrentLocaleEntityManager $currentLocaleEntityManager,
                                SiteOptionValueManager $siteOptionValueManager) {
        $this->reservationEntityManager = $reservationEntityManager;
        $this->roomEntityManager = $roomEntityManager;
        $this->currentUserEntityManager = $currentUserEntityManager;
        $this->currentLocaleEntityManager = $currentLocaleEntityManager;
        $this->siteOptionValueManager = $siteOptionValueManager;
    }

    public function indexAction() {
        $reservationQueryManager = $this->queryManager(ReservationQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $reservationQueryManager->setOrder($this->params()->fromQuery('order'));
        $reservationQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $reservationQueryManager->setUserId($this->currentUserEntityManager->get()->getId());
        $reservationQueryManager->setLocaleId($this->currentLocaleEntityManager->get()->getId());
        $reservationQuery = $reservationQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($reservationQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $this->layout()->activeMenuItemId = 'profileReservation';

        return new ViewModel([
            'reservations' => $paginator,
            'reservationQueryManager' => $reservationQueryManager,
        ]);
    }

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $currentYear = date('Y', mktime());
        $currentMonth = date('n', mktime());
        $year = $this->params()->fromRoute('year', $currentYear);
        $month = $this->params()->fromRoute('month', $currentMonth);
        $localeId = $this->currentLocaleEntityManager->get()->getId();
        $userId = $this->currentUserEntityManager->get()->getId();

        $roomEntity = $this->roomEntityManager->findOneById($id);
        if ($roomEntity == NULL || !$roomEntity->getTranslations()->containsKey($localeId)) {
            return $this->notFoundAction();
        }

        $yearLimit = 10;
        $selectedYear = $year < $currentYear ? $currentYear : ($year > $currentYear + $yearLimit - 1 ? $currentYear + $yearLimit - 1 : $year);
        $selectedMonth = (($selectedYear == $currentYear) && ($month < $currentMonth)) ? $currentMonth : $month;

        $date = new \DateTime(mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));

        $reservationEntity = $this->reservationEntityManager->findOneByComposite($userId, $id, $date);

        $this->layout()->activeMenuItemId = 'profileReservation';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'localeId' => $localeId,
            'room' => $roomEntity,
            'year' => $selectedYear,
            'month' => $selectedMonth,
            'yearLimit' => $yearLimit,
            'reservation' => $reservationEntity,
            'currency' => $company['currency'],
        ]);
    }

}
