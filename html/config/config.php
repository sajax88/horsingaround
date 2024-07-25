<?php
declare(strict_types=1);

use Phalcon\Config\Config;

$loader = (new josegonzalez\Dotenv\Loader(__DIR__ . '/../.env'))
    ->parse()
    ->toEnv(true);

return new Config([
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => $_ENV['DB_HOST'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'dbname'   => $_ENV['DB_NAME'],
        'port'     => $_ENV['DB_PORT'],
    ],
    'redis'       => [
        'host'  => $_ENV['REDIS_HOST'],
        'port'  => $_ENV['REDIS_PORT'],
        'common_index' => $_ENV['REDIS_COMMON_INDEX'],
        'session_index' => $_ENV['REDIS_SESSION_INDEX'],
    ],
    'application' => [
        'logInDb'              => true,
        'migrationsDir'        => 'db/migrations',
        'migrationsTsBased'    => false,
        'exportDataFromTables' => [],
    ],
    'telegram'    => [
        'api_url'          => $_ENV['TG_API_URL'],
        'admin_tg_chat_id' => $_ENV['TG_ADMIN_CHAT_ID'],
    ],
    'environment' => $_ENV['ENVIRONMENT'],
    'base_url' => $_ENV['BASE_URL'],
    'jwt_passphrase' => $_ENV['JWT_PASS'],
]);
