<?php

declare(strict_types=1);

namespace App\Providers;

use App\Main\Services\RedisClient;
use Phalcon\Config\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class RedisProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        /** @var Config $config */
        $config      = $di->getShared('config');
        $redisConfig = $config->get('redis');

        $di->setShared('redis', function () use ($redisConfig) {
            $client = new RedisClient([
                'host'  => $redisConfig->host,
                'port'  => $redisConfig->port,
                'database' => $redisConfig->common_index,
            ]);
            return $client;
        });
    }
}
