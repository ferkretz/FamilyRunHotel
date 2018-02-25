<?php

namespace Profile\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\Locale\CurrentLocaleEntityManager;
use Application\Service\Reservation\ReservationEntityManager;
use Application\Service\Room\RoomEntityManager;
use Application\Service\Site\SiteOptionValueManager;
use Application\Service\User\CurrentUserEntityManager;
use Profile\Controller\ReservationController;

class ReservationControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {
        $reservationEntityManager = $container->get(ReservationEntityManager::class);
        $roomEntityManager = $container->get(RoomEntityManager::class);
        $currentUserEntityManager = $container->get(CurrentUserEntityManager::class);
        $currentLocaleEntityManager = $container->get(CurrentLocaleEntityManager::class);
        $siteOptionValueManager = $container->get(SiteOptionValueManager::class);

        return new ReservationController($reservationEntityManager, $roomEntityManager, $currentUserEntityManager, $currentLocaleEntityManager, $siteOptionValueManager);
    }

}
