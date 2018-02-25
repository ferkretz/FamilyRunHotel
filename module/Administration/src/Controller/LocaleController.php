<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\Locale\AvailableForm;
use Administration\Form\Locale\PreferredForm;
use Application\Entity\Locale\LocaleEntity;
use Application\Service\Locale\LocaleEntityManager;
use Application\Service\Locale\LocaleQueryManager;

class LocaleController extends AbstractActionController {

    /**
     * Locale entity manager.
     * @var LocaleEntityManager
     */
    protected $localeEntityManager;

    public function __construct(LocaleEntityManager $localeEntityManager) {
        $this->localeEntityManager = $localeEntityManager;
    }

    public function preferredAction() {
        $localeQueryManager = $this->queryManager(LocaleQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $localeQueryManager->setOrder($this->params()->fromQuery('order'));
        $localeQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));
        $userQuery = $localeQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($userQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $localeIds = [];
        foreach ($paginator as $localeEntity) {
            $localeIds[$localeEntity->getId()] = $localeEntity->getId();
        }
        $form = new PreferredForm($localeIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    $protectException = FALSE;

                    foreach ($data['locales'] as $localeId) {
                        if ($localeId == 1) {
                            $protectException = TRUE;
                        } else {
                            $this->localeEntityManager->removeById($localeId);
                        }
                    }

                    if ($protectException) {
                        throw new \Exception('Default locale can not be deleted.');
                    }
                } else {
                    throw new \Exception('There are no locales to delete.');
                }

                return $this->redirect()->toRoute('administrationLocale');
            }
        }

        $this->layout()->activeMenuItemId = 'administrationLocale';

        return new ViewModel([
            'form' => $form,
            'locales' => $paginator,
            'localeQueryManager' => $localeQueryManager,
        ]);
    }

    public function availableAction() {
        $localeQueryManager = $this->queryManager(LocaleQueryManager::class);

        $page = $this->params()->fromQuery('page', 1);

        $localeQueryManager->setOrder($this->params()->fromQuery('order'));
        $localeQueryManager->setOrderBy($this->params()->fromQuery('orderBy'));

        $localeArray = $this->getLocaleArray($localeQueryManager->getOrder());
        $adapter = new ArrayAdapter($localeArray);
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $abcArray = $this->getAbcArray($localeArray, 10);

        $localeIds = [];
        foreach ($paginator as $locale) {
            $localeIds[$locale['id']] = $locale['name'];
        }
        $form = new AvailableForm($localeIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (!empty($data)) {
                    foreach ($data['locales'] as $localeId) {
                        $preferredArray = $this->localeEntityManager->findAllName();
                        $locales = \IntlCalendar::getAvailableLocales();
                        if (!in_array($locales[$localeId - 1], $preferredArray)) {
                            $localeEntity = new LocaleEntity();
                            $localeEntity->setName($locales[$localeId - 1]);
                            $this->localeEntityManager->insert($localeEntity);
                        }
                    }
                } else {
                    throw new \Exception('There are no locales to add.');
                }

                return $this->redirect()->toRoute('administrationLocale', ['action' => 'available']);
            }
        }


        $this->layout()->activeMenuItemId = 'administrationLocale';

        return new ViewModel([
            'form' => $form,
            'abc' => $abcArray,
            'locales' => $paginator,
            'localeQueryManager' => $localeQueryManager,
        ]);
    }

    private function getLocaleArray($order = 'ASC') {
        $preferredArray = $this->localeEntityManager->findAllName();
        $localeArray = [];

        $locales = \IntlCalendar::getAvailableLocales();
        $count = count($locales);

        if ($order == 'DESC') {
            for ($i = $count; $i > 0; $i --) {
                if (!in_array($locales[$i - 1], $preferredArray)) {
                    $localeArray[] = [
                        'id' => $i,
                        'name' => $locales[$i - 1]
                    ];
                }
            }
        } else {
            for ($i = 1; $i <= $count; $i ++) {
                if (!in_array($locales[$i - 1], $preferredArray)) {
                    $localeArray[] = [
                        'id' => $i,
                        'name' => $locales[$i - 1]
                    ];
                }
            }
        }

        return $localeArray;
    }

    private function getAbcArray($localeArray,
                                 $itemCountPerPage) {
        $abcArray = [];
        $lastLetter = '';
        $index = 0;

        foreach ($localeArray as $locale) {
            if ($locale['name'][0] != $lastLetter) {
                $lastLetter = $locale['name'][0];
                $abcArray[intdiv($index, $itemCountPerPage) + 1] = $lastLetter;
            }
            $index++;
        }

        return $abcArray;
    }

}
