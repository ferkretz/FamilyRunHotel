<?php

namespace Administration;

class Module {

    const VERSION = '3.1.0';

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

}
