<?php

declare(strict_types=1);

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Redis as RedisSessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;


class SessionProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        /** @var Config $config */
        $config      = $di->getShared('config');
        $redisConfig = $config->get('redis');

        $di->setShared('session', function () use ($redisConfig) {
            $session = new SessionManager();
            $factory = new AdapterFactory(new SerializerFactory());
            $adapter = new RedisSessionAdapter(
                $factory,
                [
                    'host'  => $redisConfig->host,
                    'port'  => $redisConfig->port,
                    'index' => $redisConfig->session_index,
                ]
            );
            // The session will be started when the service is requested for the first time
            $session->setAdapter($adapter)->start();

            return $session;
        });
    }
}
