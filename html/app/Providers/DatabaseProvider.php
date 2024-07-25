<?php

declare(strict_types=1);

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

class DatabaseProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        /** @var Config $config */
        $config = $di->getShared('config');
        $dbConfig = $config->get('database');

        $di->setShared(
            'db',
            function () use ($dbConfig) {
                return new PdoMysql(
                    [
                        'host'     => $dbConfig->host,
                        'username' => $dbConfig->username,
                        'password' => $dbConfig->password,
                        'dbname'   => $dbConfig->dbname,
                        'port'     => $dbConfig->port,
                    ]
                );
            },
        );
    }
}
