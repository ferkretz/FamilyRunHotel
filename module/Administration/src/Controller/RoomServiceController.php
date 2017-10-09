<?php

namespace Administration\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Administration\Entity\RoomService;
use Administration\Form\RoomServiceAddForm;
use Administration\Form\RoomServiceEditForm;
use Administration\Form\RoomServiceIndexForm;
use Administration\Service\RoomServiceQueryManager;
use Administration\Service\RoomServiceManager;
use Application\Service\Localizator;

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

    public function __construct(RoomServiceQueryManager $roomServiceQueryManager,
                                RoomServiceManager $roomServiceManager,
                                Localizator $localizator) {
        $this->roomServiceQueryManager = $roomServiceQueryManager;
        $this->roomServiceManager = $roomServiceManager;
        $this->localizator = $localizator;
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

                return $this->redirect()->toRoute('admin-services');
            } else {
                throw new \Exception(current($form->getMessages()['roomServices'][0]));
            }
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
        $localeInfo = localeconv();
        $locales = $this->localizator->getSupportedLocales();
        $form = new RoomServiceEditForm($locales);

        $id = $this->params()->fromRoute('id', -1);
        $translation = $this->params()->fromRoute('translation', 'none');
        $roomService = $this->roomServiceManager->findById($id);
        if ($roomService == NULL) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($translation != 'none') {
                $form->getInputFilter()->get('translationSummary')->setRequired(TRUE);
            }

            if ($form->isValid()) {
                $data['price'] = str_replace($localeInfo['decimal_point'], '.', $data['price']);
                $roomService->setData($data);
                $this->roomServiceManager->update();
            }
        } else {
            $data = $roomService->getData($translation);
            $data['price'] = number_format($data['price'], 2, $localeInfo['decimal_point'], '');
            $form->setData($data);
        }

        return new ViewModel([
            'id' => $id,
            'translation' => $translation,
            'translationName' => \Locale::getDisplayName($translation),
            'fallbackLocaleName' => current($this->localizator->getFallbackLocale()),
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
                $roomService->setData($data);
                $this->roomServiceManager->add($roomService);

                return $this->redirect()->toRoute('admin-services');
            }
        }

        return new ViewModel([
            'fallbackLocaleName' => current($this->localizator->getFallbackLocale()),
            'form' => $form,
        ]);
    }

    protected function translate($message) {
        $this->translator()->translate($message);
    }

}
