<?php
declare(strict_types=1);

namespace App\Api;

use App\Api\Http\ApiResponse;
use App\Plugins\ApiPageNotFoundPlugin;
use App\Plugins\JsonOutputPlugin;
use App\Plugins\TokenVerificationPlugin;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

/**
 * Used for various integrations with external systems
 */
class Module implements ModuleDefinitionInterface {
    public function registerAutoloaders(DiInterface $container = null) {

    }

    public function registerServices(DiInterface $container) {
        // Dispatcher, before-route plugins
        $container->setShared(
            'dispatcher',
            function () {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace(
                    'App\Api\Controllers'
                );

                $eventsManager = new EventsManager();
                // Check access with JWT
                $eventsManager->attach('dispatch:beforeExecuteRoute', new TokenVerificationPlugin());
                // Set 404 code if api method not found
                $eventsManager->attach('dispatch:beforeException', new ApiPageNotFoundPlugin());
                // Output json
                $eventsManager->attach('dispatch:afterDispatchLoop', new JsonOutputPlugin());

                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            }
        );

        // JSON response
        $container->setShared(
            'response',
            function () {
                return new ApiResponse();
            }
        );

        // View not needed here
        $container->set(
            'view',
            function () {
                return (new View())->disable();
            }
        );

    }
}
