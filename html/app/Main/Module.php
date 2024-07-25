<?php
declare(strict_types=1);

namespace App\Main;

use App\Plugins\AccessPlugin;
use App\Plugins\WebsitePageNotFoundPlugin;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

class Module implements ModuleDefinitionInterface {
    public function registerAutoloaders(DiInterface $container = null) {}

    public function registerServices(DiInterface $container) {
        // Set dispatcher for UI part of the app
        $container->set(
            'dispatcher',
            function () {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace('App\Main\Controllers');

                $eventsManager = new EventsManager();
                // Handle a Not Found exception in Dispatcher, redirect to 404 page
                $eventsManager->attach('dispatch:beforeException', new WebsitePageNotFoundPlugin());
                // Check access with session and ACL
                $eventsManager->attach('dispatch:beforeExecuteRoute', new AccessPlugin());
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            }
        );

        // Views
        $viewsDir = $container->offsetGet('rootPath') . '/app/Main/Views';
        $container->setShared('view', function () use ($viewsDir) {
            $view = new View();
            $view->setViewsDir($viewsDir);
            $view->registerEngines(['.volt' => 'volt']);
            return $view;
        });

        // Use Volt template engine
        $view = $container->getShared('view');
        $container->setShared('volt', function () use ($view, $container) {
            $volt = new VoltEngine($view, $container);
            $volt->setOptions([
                'path' => $container->offsetGet('rootPath') . '/cache/volt/',
            ]);
            return $volt;
        });
    }
}
