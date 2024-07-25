<?php

declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router;


class RouterProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        $di->setShared(
            'router',
            function () {
                // Disable default simple routing, we'll start from module
                $router = new Router();
                $router->removeExtraSlashes(true);
                $router->setDefaultModule('main');

                $router->add(
                    '/api/:controller/:action',
                    [
                        'module' => 'api',
                        'controller' => 1,
                        'action'     => 2,
                    ]
                );

                $router->add(
                    '/api/:controller',
                    [
                        'module' => 'api',
                        'controller' => 1,
                        'action'     => 'index',
                    ]
                );

                $router->add(
                    '/',
                    [
                        'controller' => 'index',
                        'action'     => 'index',
                    ]
                );

                return $router;
            }
        );
    }
}
