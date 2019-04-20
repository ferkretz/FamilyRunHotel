<?php

namespace Application\Controller\Administration;

use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Application\Form\Administration\Point\AddForm;
use Application\Form\Administration\Point\EditForm;
use Application\Form\Administration\Point\ListForm;
use Application\Entity\Point\Point;
use Application\Entity\Point\PointTranslation;
use Application\Service\Option\SettingsManager;
use Application\Service\Point\PointManager;
use Application\Service\Point\PointQueryManager;
use Application\Service\User\AccessManager;

class PointController extends AbstractActionController {

    /**
     * Settings manager.
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Point entity manager.
     * @var PointManager
     */
    protected $pointManager;

    /**
     * Point query manager.
     * @var PointQueryManager
     */
    protected $pointQueryManager;

    /**
     * Access manager.
     * @var AccessManager
     */
    protected $accessManager;

    public function __construct(SettingsManager $settingsManager,
                                PointManager $pointManager,
                                PointQueryManager $pointQueryManager,
                                AccessManager $accessManager) {
        $this->settingsManager = $settingsManager;
        $this->pointManager = $pointManager;
        $this->pointQueryManager = $pointQueryManager;
        $this->accessManager = $accessManager;
    }

    public function listAction() {
        $this->accessManager->currentUserRedirect('manage.settings');

        $page = $this->params()->fromQuery('page', 1);
        $this->pointQueryManager->setOrder($this->params()->fromQuery('order'));
        $this->pointQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!in_array($locale, $locales)) {
            return $this->notFoundAction();
        }
        $this->pointQueryManager->setLocale($locale);
        $pointQuery = $this->pointQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($pointQuery, false));
        $points = new Paginator($adapter);
        $points->setDefaultItemCountPerPage($this->settingsManager->getSetting(SettingsManager::ROWS_PER_PAGE));
        $points->setCurrentPageNumber($page);

        $pointIds = [];
        foreach ($points as $point) {
            $pointIds[$point->getId()] = $point->getId();
        }
        $form = new ListForm($pointIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $protectException = false;

                    foreach ($data['points'] as $pointId) {
                        if ($pointId == 1) {
                            $protectException = true;
                        } else {
                            $this->pointManager->removeById($pointId);
                        }
                    }

                    if ($protectException) {
                        throw new \Exception('Default POI can not be deleted.');
                    }
                } else {
                    throw new \Exception('There are no POIs to delete.');
                }

                return $this->redirect()->toRoute(null);
            }
        }

        return new ViewModel([
            'form' => $form,
            'points' => $points,
            'pointQueryManager' => $this->pointQueryManager,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function editAction() {
        $this->accessManager->currentUserRedirect('manage.settings');

        $id = $this->params()->fromRoute('id', -1);
        $point = $this->pointManager->findOneById($id);
        if (!$point) {
            return $this->notFoundAction();
        }
        $locale = $this->params()->fromQuery('locale', $this->settingsManager->getSetting(SettingsManager::LOCALE, true));
        $locales = $this->settingsManager->getSetting(SettingsManager::LOCALES);
        if (!(in_array($locale, $locales) && $point->getTranslations()->containsKey($this->settingsManager->getSetting(SettingsManager::LOCALE, true)))) {
            return $this->notFoundAction();
        }

        $form = new EditForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {
                $point->setIcon($data['icon']);
                $point->setLatitude($data['latitude']);
                $point->setLongitude($data['longitude']);
                if ($point->getTranslations()->containsKey($locale)) {
                    $translation = $point->getTranslations()->get($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $this->pointManager->update();
                } else {
                    $translation = new PointTranslation();
                    $translation->setPoint($point);
                    $translation->setLocale($locale);
                    $translation->setSummary($data['summary']);
                    $translation->setDescription($data['description']);
                    $point->getTranslations()->add($translation);
                    $this->pointManager->insert($point);
                }
            }
        } else {
            $data['icon'] = $point->getIcon();
            $data['latitude'] = $point->getLatitude();
            $data['longitude'] = $point->getLongitude();
            if ($point->getTranslations()->containsKey($locale)) {
                $translation = $point->getTranslations()->get($locale);
                $data['summary'] = $translation->getSummary();
                $data['description'] = $translation->getDescription();
            }
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'placeholder' => $point->getTranslations()->get($this->settingsManager->getSetting(SettingsManager::LOCALE, true)),
            'locale' => $locale,
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

    public function addAction() {
        $this->accessManager->currentUserRedirect('manage.settings');

        $form = new AddForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $point = new Point();
                $point->setIcon($data['icon']);
                $point->setLatitude($data['latitude']);
                $point->setLongitude($data['longitude']);
                $translation = new PointTranslation();
                $translation->setPoint($point);
                $translation->setLocale($this->settingsManager->getSetting(SettingsManager::LOCALE, true));
                $translation->setSummary($data['summary']);
                $translation->setDescription($data['description']);
                $point->getTranslations()->add($translation);
                $this->pointManager->insert($point);

                return $this->redirect()->toRoute(null, ['action' => 'edit', 'id' => $point->getId()], ['query' => ['locale' => $this->settingsManager->getSetting(SettingsManager::LOCALE, true)]]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'settingsManager' => $this->settingsManager
        ]);
    }

}
