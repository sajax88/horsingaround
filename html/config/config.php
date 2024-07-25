<?php
declare(strict_types=1);

use Phalcon\Config\Config;

return new Config([
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'dbname'   => getenv('DB_NAME'),
        'port'     => getenv('DB_PORT'),
    ],
    'redis'       => [
        'host'  => getenv('REDIS_HOST'),
        'port'  => getenv('REDIS_PORT'),
        'common_index' => getenv('REDIS_COMMON_INDEX'),
        'session_index' => getenv('REDIS_SESSION_INDEX'),
    ],
    'application' => [
        'logInDb'              => true,
        'migrationsDir'        => 'db/migrations',
        'migrationsTsBased'    => false,
        'exportDataFromTables' => [],
    ],
    'telegram'    => [
        'api_url'          => getenv('TG_API_URL'),
        'admin_tg_chat_id' => getenv('TG_ADMIN_CHAT_ID'),
    ],
    'environment' => getenv('ENVIRONMENT'),
    'base_url' => getenv('BASE_URL'),
    'jwt_passphrase' => getenv('JWT_PASS'),
]);
