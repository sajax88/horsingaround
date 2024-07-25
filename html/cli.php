<?php

declare(strict_types=1);

use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Di\ServiceProviderInterface;


$rootPath = __DIR__;
require_once $rootPath . '/vendor/autoload.php';

$container  = new CliDI();

$dispatcher = new Dispatcher();
$dispatcher->setDefaultNamespace('App\Tasks');
$container->setShared('dispatcher', $dispatcher);

$container->offsetSet('rootPath', function () use ($rootPath) {
    return $rootPath;
});

/** @var ServiceProviderInterface[] $providers */
$providers = require_once $rootPath . '/config/cli_providers.php';
foreach ($providers as $provider) {
    $container->register(new $provider());
}

$console = new Console($container);

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    $console->handle($arguments);
} catch (\Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
