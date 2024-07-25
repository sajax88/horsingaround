<?php
// Only the services needed for CLI app
return [
    \App\Providers\ConfigProvider::class,
    \App\Providers\DatabaseProvider::class,
    \App\Providers\RedisProvider::class,
    \App\Providers\LoggerProvider::class,
    \App\Providers\NotificationsProvider::class,
];
