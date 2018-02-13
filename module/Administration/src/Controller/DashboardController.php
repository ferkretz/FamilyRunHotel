<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Form\Dashboard\FeaturesForm;
use Administration\Form\Dashboard\LookForm;
use Application\Entity\Site\SiteOptionEntity;
use Application\Service\Site\SiteOptionEntityManager;
use Application\Service\Site\SiteOptionValueManager;

class DashboardController extends AbstractActionController {

    /**
     * Site option value manager
     * @var SiteOptionValueManager
     */
    protected $siteOptionValueManager;

    /**
     * Site option entity manager.
     * @var SiteOptionEntityManager
     */
    protected $siteOptionEntityManager;

    public function __construct(SiteOptionValueManager $siteOptionValueManager,
                                SiteOptionEntityManager $siteOptionEntityManager) {
        $this->siteOptionValueManager = $siteOptionValueManager;
        $this->siteOptionEntityManager = $siteOptionEntityManager;
    }

    public function featuresAction() {
        $form = new FeaturesForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                // company
                $companyData = [
                    'name' => $data['companyName'],
                    'i18n' => $data['companyI18n'] == 1 ? TRUE : FALSE, // convert int to bool, because of checkbox
                    'fullName' => $data['companyFullName'],
                    'email' => $data['companyEmail'],
                    'address' => $data['companyAddress'],
                    'phone' => $data['companyPhone'],
                    'currency' => $data['companyCurrency'],
                ];
                $companyEntity = $this->siteOptionEntityManager->findOneByName('company');
                if (!$companyEntity) {
                    $companyEntity = new SiteOptionEntity();
                    $companyEntity->setName('company');
                    $companyEntity->setValue(serialize($companyData));
                    $this->siteOptionEntityManager->insert($companyEntity);
                } else {
                    $companyEntity->setValue(serialize($companyData));
                    $this->siteOptionEntityManager->update();
                }

                // upload
                $uploadData = [
                    'jpegQuality' => $data['uploadJpegQuality'],
                    'minImageSize' => $data['uploadMinImageSize'],
                    'maxImageSize' => $data['uploadMaxImageSize'],
                    'thumbnailWidth' => $data['uploadThumbnailWidth'],
                ];
                $uploadEntity = $this->siteOptionEntityManager->findOneByName('upload');
                if (!$uploadEntity) {
                    $uploadEntity = new SiteOptionEntity();
                    $uploadEntity->setName('upload');
                    $uploadEntity->setValue(serialize($uploadData));
                    $this->siteOptionEntityManager->insert($uploadEntity);
                } else {
                    $uploadEntity->setValue(serialize($uploadData));
                    $this->siteOptionEntityManager->update();
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'features']); // refresh because of brand name change
            }
        } else {
            // company
            $defaultCompany = [
                'name' => 'Family-run Hotel',
                'i18n' => FALSE,
                'fullName' => 'Family-run Hotel Inc.',
                'email' => NULL,
                'address' => NULL,
                'phone' => NULL,
                'currency' => 'USD',
            ];
            $company = $this->siteOptionValueManager->findOneByName('company', $defaultCompany);
            $companyData = [
                'companyName' => $company['name'],
                'companyI18n' => $company['i18n'] ? 1 : 0, // convert bool to int, because of checkbox
                'companyFullName' => $company['fullName'],
                'companyEmail' => $company['email'],
                'companyAddress' => $company['address'],
                'companyPhone' => $company['phone'],
                'companyCurrency' => $company['currency'],
            ];

            // upload
            $defaultUpload = [
                'jpegQuality' => 75,
                'minImageSize' => 256,
                'maxImageSize' => 7680,
                'thumbnailWidth' => 196,
            ];
            $upload = $this->siteOptionValueManager->findOneByName('upload', $defaultUpload);
            $uploadData = [
                'uploadJpegQuality' => $upload['jpegQuality'],
                'uploadMinImageSize' => $upload['minImageSize'],
                'uploadMaxImageSize' => $upload['maxImageSize'],
                'uploadThumbnailWidth' => $upload['thumbnailWidth'],
            ];

            $form->setData(array_merge($companyData, $uploadData));
        }

        $this->layout()->activeMenuItemId = 'administrationDashboard';

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function lookAction() {
        $form = new LookForm();

        $decimalFormatter = new \NumberFormatter(NULL, \NumberFormatter::DECIMAL);
        $decimalFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 7);
        $decimalSeparator = $decimalFormatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                // look
                $lookData = [
                    'theme' => $data['lookTheme'],
                    'renderHeader' => $data['lookRenderHeader'],
                    'barStyle' => $data['lookBarStyle'],
                ];
                $lookEntity = $this->siteOptionEntityManager->findOneByName('look');
                if (!$lookEntity) {
                    $lookEntity = new SiteOptionEntity();
                    $lookEntity->setName('look');
                    $lookEntity->setValue(serialize($lookData));
                    $this->siteOptionEntityManager->insert($lookEntity);
                } else {
                    $lookEntity->setValue(serialize($lookData));
                    $this->siteOptionEntityManager->update();
                }

                // google
                $data['googleLatitude'] = str_replace($decimalSeparator, '.', $data['googleLatitude']);
                $data['googleLongitude'] = str_replace($decimalSeparator, '.', $data['googleLongitude']);
                $googleData = [
                    'enable' => $data['googleEnable'] == 1 ? TRUE : FALSE, // convert int to bool, because of checkbox
                    'latitude' => $data['googleLatitude'],
                    'longitude' => $data['googleLongitude'],
                    'zoom' => $data['googleZoom'],
                ];
                $googleEntity = $this->siteOptionEntityManager->findOneByName('google');
                if (!$googleEntity) {
                    $googleEntity = new SiteOptionEntity();
                    $googleEntity->setName('google');
                    $googleEntity->setValue(serialize($googleData));
                    $this->siteOptionEntityManager->insert($googleEntity);
                } else {
                    $googleEntity->setValue(serialize($googleData));
                    $this->siteOptionEntityManager->update();
                }

                return $this->redirect()->toRoute(NULL, ['action' => 'look']); // refresh because of theme change
            }
        } else {
            // look
            $defaultLook = [
                'theme' => 'cofee',
                'renderHeader' => 'home',
                'barStyle' => 'default',
            ];
            $look = $this->siteOptionValueManager->findOneByName('look', $defaultLook);
            $lookData = [
                'lookTheme' => $look['theme'],
                'lookRenderHeader' => $look['renderHeader'],
                'lookBarStyle' => $look['barStyle'],
            ];

            // google
            $defaultGoogle = [
                'enable' => FALSE,
                'latitude' => 0,
                'longitude' => 0,
                'zoom' => 15,
            ];
            $google = $this->siteOptionValueManager->findOneByName('google', $defaultGoogle);
            $googleData = [
                'googleEnable' => $google['enable'] ? 1 : 0, // convert bool to int, because of checkbox
                'googleLatitude' => $decimalFormatter->format($google['latitude']),
                'googleLongitude' => $decimalFormatter->format($google['longitude']),
                'googleZoom' => $google['zoom'],
            ];

            $form->setData(array_merge($lookData, $googleData));
        }

        $this->layout()->activeMenuItemId = 'administrationDashboard';

        return new ViewModel([
            'form' => $form,
        ]);
    }

}
