<?php

namespace Application\Controller\Administration;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Service\AddForm;
use Application\Form\Administration\Service\EditForm;
use Application\Form\Administration\Service\ListForm;
use Application\Entity\Service\Service;
use Application\Entity\Service\ServiceTranslation;
use Application\Service\Option\SettingsManager;
use Application\Service\Service\ServiceManager;
use Application\Service\Service\ServiceQueryManager;
use Application\Service\User\AccessManager;

class ServiceController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

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
                                ServiceManager $serviceManager,
                                ServiceQueryManager $serviceQueryManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->serviceManager = $serviceManager;
        $this->serviceQueryManager = $serviceQueryManager;
        $this->accessManager = $accessManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('list.offers');

        $page = $this->params()->fromQuery('page', 1);
        $this->serviceQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->serviceQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!in_array($locale, $locales)) {
            return $this->notFoundAction();
        }
        $this->serviceQueryManager->setLocale($locale);
        $serviceQuery = $this->serviceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($serviceQuery, false));
        $services = new Paginator($adapter);
        $services->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $services->setCurrentPageNumber($page);

        $serviceIds = [];
        foreach ($services as $service) {
            $serviceIds[$service->getId()] = $service->getId();
        }
        $form = new ListForm($serviceIds);

        if ($this->getRequest()->isPost()) {
            $this->accessManager->currentUserTry('delete.offers');

            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['services'] as $serviceId) {
                        $this->serviceManager->removeById($serviceId);
                    }
                } else {
                    throw new \Exception('There are no services to delete.');
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'services' => $services,
            'serviceQueryManager' => $this->serviceQueryManager,
            'settingsManager' => $this->settingsManager,
            'accessManager' => $this->accessManager
        ]);
    }

    public function editAction() {
        $this->accessManager->currentUserRedirect('edit.offers');

        $id = $this->params()->fromRoute('id', -1);
        $service = $this->serviceManager->findOneById($id);
        if (!$service) {
            return $this->notFoundAction();
        }
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!(in_array($locale, $locales) && $service->getTranslations()->containsKey($this->settingsManager->getSetting(SettingsManager::LOCALE, true)))) {
            return $this->notFoundAction();
        }

        $form = new EditForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {
                $service->setPrice($data['price']);
                if ($service->getTranslations()->containsKey($locale)) {
                    $translation = $service->getTranslations()->get($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $this->serviceManager->update();
                } else {
                    $translation = new ServiceTranslation();
                    $translation->setService($service);
                    $translation->setLocale($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $service->getTranslations()->add($translation);
                    $this->serviceManager->insert($service);
                }
            }
        } else {
            $data['price'] = $service->getPrice();
            if ($service->getTranslations()->containsKey($locale)) {
                $translation = $service->getTranslations()->get($locale);
                $data['summary'] = $translation->getSummary();
                $data['description'] = $translation->getDescription();
            }
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'placeholder' => $service->getTranslations()->get($this->settingsManager->getSetting(SettingsManager::LOCALE, true)),
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
                $service = new Service();
                $service->setPrice($data['price']);
                $translation = new ServiceTranslation();
                $translation->setService($service);
                $translation->setLocale($this->settingsManager->getSetting(SettingsManager::LOCALE, true));
                $translation->setSummary($data['summary']);
                $translation->setDescription($data['description']);
                $service->getTranslations()->add($translation);
                $this->serviceManager->insert($service);

                return $this->redirect()->toRoute(null, ['action' => 'edit', 'id' => $service->getId()], ['query' => ['locale' => $this->settingsManager->getSetting(SettingsManager::LOCALE, true)]]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

}
