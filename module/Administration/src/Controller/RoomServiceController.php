<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Form\RoomServiceAddForm;
use Administration\Form\RoomServiceEditForm;
use Administration\Form\RoomServiceIndexForm;
use Administration\Service\RoomServiceQueryManager;
use Application\Entity\RoomService;
use Application\Entity\RoomServiceTranslation;
use Application\Service\Localizator;
use Application\Service\RoomServiceManager;
use Application\Service\SiteOptionManager;

class RoomServiceController extends AbstractActionController {

    /**
     * RoomServiceQuery manager.
     * @var RoomServiceQueryManager
     */
    protected $roomServiceQueryManager;

    /**
     * RoomService manager.
     * @var RoomServiceManager
     */
    protected $roomServiceManager;
    protected $localizator;

    /**
     * Picture manager.
     * @var OptionManager
     */
    protected $optionManager;

    public function __construct(RoomServiceQueryManager $roomServiceQueryManager,
                                RoomServiceManager $roomServiceManager,
                                Localizator $localizator,
                                SiteOptionManager $optionManager) {
        $this->roomServiceQueryManager = $roomServiceQueryManager;
        $this->roomServiceManager = $roomServiceManager;
        $this->localizator = $localizator;
        $this->optionManager = $optionManager;
    }

    public function indexAction() {
        $page = $this->params()->fromQuery('page', 1);
        $orderBy = $this->params()->fromQuery('orderBy', RoomServiceQueryManager::ORDER_BY_ID);
        $order = $this->params()->fromQuery('order', RoomServiceQueryManager::ORDER_ASC);

        $this->roomServiceQueryManager->setOrder($order);
        $this->roomServiceQueryManager->setOrderBy($orderBy);
        $roomServiceQuery = $this->roomServiceQueryManager->getQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($roomServiceQuery, FALSE));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);

        $roomServiceIds = [];
        $translationList = [];
        foreach ($paginator as $roomService) {
            $roomServiceIds[] = $roomService->getId();

            $translationArr = [];
            foreach ($roomService->getTranslations() as $translation) {
                $translationArr[] = \Locale::getDisplayName($translation->getLocale());
            }
            $translationList[$roomService->getId()] = implode(', ', $translationArr);
        }
        $form = new RoomServiceIndexForm($roomServiceIds);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                if (count($data)) {
                    foreach ($data['roomServices'] as $index) {
                        $roomServices[] = $paginator->getItem($index + 1);
                    }
                    foreach ($roomServices as $roomService) {
                        $this->roomServiceManager->remove($roomService);
                    }
                } else {
                    throw new \Exception('There are no services to delete.');
                }

                return $this->redirect()->toRoute('administrationService');
            } else {
                throw new \Exception(current($form->getMessages()['roomServices'][0]));
            }
        }

        $this->layout()->navBarData->setActiveItemId('administrationService');
        if ($this->optionManager->findCurrentValueByName('headerShow') == 'everywhere') {
            $this->layout()->headerData->setVisible(TRUE);
        }

        return new ViewModel([
            'form' => $form,
            'roomServices' => $paginator,
            'translationList' => $translationList,
            'orderBy' => $this->roomServiceQueryManager->getOrderBy(),
            'order' => $this->roomServiceQueryManager->getOrder(),
            'requiredQuery' => ['orderBy' => $this->roomServiceQueryManager->getOrderBy(), 'order' => $this->roomServiceQueryManager->getOrder()],
        ]);
    }

    public function editAction() {
        $form = new RoomServiceEditForm($this->localizator->getSupportedLocaleNames());

        $id = $this->params()->fromRoute('id', -1);
        $translationLocale = $this->params()->fromRoute('translationLocale', 'none');
        $supportedLocales = array_merge(['none'], $this->localizator->getSupportedLocales());
        $roomService = $this->roomServiceManager->findById($id);
        if ($roomService == NULL || !in_array($translationLocale, $supportedLocales)) {
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
                $roomService->setSummary($data['summary']);
                $roomService->setPrice(str_replace(localeconv()['decimal_point'], '.', $data['price']));
                $roomService->setCurrency($data['currency']);
                $roomService->setDescription($data['description']);
                if ($translationLocale != 'none') {
                    $translation = new RoomServiceTranslation();
                    $translation->setSummary($data['translationSummary']);
                    $translation->setDescription($data['translationDescription']);
                    $roomService->setTranslation($translationLocale, $translation);
                }
                $this->roomServiceManager->update();
            }
        } else {
            $data['summary'] = $roomService->getSummary();
            $data['price'] = number_format($roomService->getPrice(), 2, localeconv()['decimal_point'], '');
            $data['currency'] = $roomService->getCurrency();
            $data['description'] = $roomService->getDescription();
            if ($translationLocale != 'none') {
                $translation = $roomService->getTranslation($translationLocale);
                if ($translation) {
                    $data['translationSummary'] = $translation->getSummary();
                    $data['translationDescription'] = $translation->getDescription();
                }
            }
            $form->setData($data);
        }

        $this->layout()->navBarData->setActiveItemId('administrationService');
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
        $form = new RoomServiceAddForm();
        $roomService = new RoomService();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $roomService->setSummary($data['summary']);
                $roomService->setPrice(str_replace(localeconv()['decimal_point'], '.', $data['price']));
                $roomService->setCurrency($data['currency']);
                $roomService->setDescription($data['description']);
                $this->roomServiceManager->add($roomService);

                return $this->redirect()->toRoute('administrationService', ['action' => 'edit', 'id' => $roomService->getId()]);
            }
        }

        $this->layout()->navBarData->setActiveItemId('administrationService');
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
