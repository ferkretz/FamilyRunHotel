<?php

namespace Application\Controller\Index\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\Index\IndexController;

class IndexControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container,
                             $requestedName,
                             array $options = NULL) {

        return new IndexController();
    }

}
