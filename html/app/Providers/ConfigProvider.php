<?php

declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        $configPath = $di->offsetGet('rootPath') . '/config/config.php';
        $config     = require_once $configPath;
        $di->setShared('config', function () use ($config) {
            return $config;
        });

        // Shortcut for dev environment check
        $di->offsetSet('isDevEnv',function () use ($config) {
            return $config->environment == 'DEV';
        });
    }
}
