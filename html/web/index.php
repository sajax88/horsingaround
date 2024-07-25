<?php
declare(strict_types=1);

use Phalcon\Di\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Application;

function setupDI(): void {
    $diContainer = new FactoryDefault();

    $rootPath = realpath('..');
    require_once $rootPath . '/vendor/autoload.php';

    $diContainer->offsetSet('rootPath', function () use ($rootPath) {
        return $rootPath;
    });

    /** @var ServiceProviderInterface[] $providers */
    $providers = require_once $rootPath . '/config/providers.php';
    foreach ($providers as $provider) {
        $diContainer->register(new $provider());
    }

    Di::setDefault($diContainer);
}

function setupApplication(): Application {
    $application = new Application(Di::getDefault());
    $application->registerModules(
        [
            // Main part of the system, authorized users working with requests
            'main' => [
                'className' => \App\Main\Module::class,
                'path'      => '../app/Main/Module.php',
            ],
            // Used for various integrations with external systems
            'api' => [
                'className' => \App\Api\Module::class,
                'path'      => '../app/Api/Module.php',
            ],
        ]
    );
    return $application;
}


try {
    // Setup Dependency Injection
    setupDI();

    // Set debug mode if we're in DEV environment
    if (Di::getDefault()->get('isDevEnv')) {
        (new Phalcon\Support\Debug())->listen();
    }

    // Register modules in the app
    $application = setupApplication();

    // Handle request
    $response = $application->handle($_SERVER["REQUEST_URI"]);
    $response->send();
} catch (\Throwable $e) {
    // Re-throw the exception for dev for easier debug
    if (Di::getDefault()->get('isDevEnv')) {
        throw $e;
    } else {
        // This is our last resort - normally we should not get here
        error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        print('Something is very wrong, but we are working on it!');
    }
}
