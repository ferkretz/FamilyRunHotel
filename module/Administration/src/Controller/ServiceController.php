<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\Service\AddForm;
use Administration\Form\Service\EditForm;
use Administration\Form\Service\IndexForm;
use Application\Entity\Service\ServiceEntity;
use Application\Entity\Service\ServiceTranslationEntity;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Service\ServiceEntityManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\Site\SiteOptionValueManager;

class ServiceController extends AbstractActionController {

    /**
     * Service entity manager.
     * @var ServiceEntityManager
     */
    protected $serviceEntityManager;

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

    public function __construct(ServiceEntityManager $serviceEntityManager,
                                LocaleEntityManager $localeEntityManager,
                                SiteOptionValueManager $siteOptionValueManager) {
        $this->serviceEntityManager = $serviceEntityManager;
        $this->localeEntityManager = $localeEntityManager;
        $this->siteOptionValueManager = $siteOptionValueManager;
    }

    public function indexAction() {
        $serviceQueryManager = $this->queryManager(ServiceQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $serviceQueryManager->setOrder($this->params()->fromQuery('order'));
        $serviceQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $serviceQueryManager->setLocaleId($this->params()->fromQuery('localeId'), $this->localeEntityManager->findAllId());
        $serviceQuery = $serviceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($serviceQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $serviceIds = [];
        foreach ($paginator as $serviceEntity) {
            $serviceIds[$serviceEntity->getId()] = $serviceEntity->getId();
        }
        $form = new IndexForm($serviceIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['services'] as $serviceId) {
                        $this->serviceEntityManager->removeById($serviceId);
                    }
                } else {
                    throw new \Exception('There are no services to delete.');
                }

                return $this->redirect()->toRoute(NULL);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationService';

        $defaultCompany = [
            'currency' => 'USD',
        ];
        $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);

        return new ViewModel([
            'form' => $form,
            'services' => $paginator,
            'locales' => $this->localeEntityManager->findAllDisplayName(),
            'serviceQueryManager' => $serviceQueryManager,
            'currency' => $company['currency'],
        ]);
    }

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $serviceEntity = $this->serviceEntityManager->findOneById($id);
        if ($serviceEntity == NULL) {
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
                $serviceEntity->setPrice($decimalFormatter->parse($data['price']));
                if ($serviceEntity->getTranslations()->containsKey($localeId)) {
                    $translationEntity = $serviceEntity->getTranslations()->get($localeId);
                    $translationEntity->setSummary($data['summary']);
                    $translationEntity->setDescription($data['description']);
                    $this->serviceEntityManager->update();
                } else {
                    $translationEntity = new ServiceTranslationEntity();
                    $translationEntity->setService($serviceEntity);
                    $translationEntity->setLocale($this->localeEntityManager->findOneById($localeId));
                    $translationEntity->setSummary($data['summary']);
                    $translationEntity->setDescription($data['description']);
                    $serviceEntity->getTranslations()->add($translationEntity);
                    $this->serviceEntityManager->insert($serviceEntity);
                }
            }
        } else {
            $data['price'] = $decimalFormatter->format($serviceEntity->getPrice());
            if ($serviceEntity->getTranslations()->containsKey($localeId)) {
                $translationEntity = $serviceEntity->getTranslations()->get($localeId);
                $data['summary'] = $translationEntity->getSummary();
                $data['description'] = $translationEntity->getDescription();
            }
            $form->setData($data);
        }

        $this->layout()->activeMenuItemId = 'administrationService';

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
                $serviceEntity = new ServiceEntity();
                $serviceEntity->setPrice($decimalFormatter->parse($data['price']));
                $translationEntity = new ServiceTranslationEntity();
                $translationEntity->setService($serviceEntity);
                $translationEntity->setLocale($this->localeEntityManager->findOneById(1));
                $translationEntity->setSummary($data['summary']);
                $translationEntity->setDescription($data['description']);
                $serviceEntity->getTranslations()->add($translationEntity);
                $this->serviceEntityManager->insert($serviceEntity);

                return $this->redirect()->toRoute(NULL, ['action' => 'edit', 'id' => $serviceEntity->getId()], ['query' => ['localeId' => 1]]);
            }
        }

        $this->layout()->activeMenuItemId = 'administrationService';

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

}
