<?php

declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Logger;

class LoggerProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        $rootPath = $di->offsetGet('rootPath');
        $di->setShared(
            'main_logger',
            function () use ($rootPath) {
                return new Logger(
                    'main',
                    [
                        'file' => new Stream($rootPath . '/logs/app.log'),
                    ]
                );
            },
        );

        // Only for logging outgoing notifications (e.g. to Telegram)
        $di->setShared(
            'notifications_logger',
            function () use ($rootPath) {
                return new Logger(
                    'notifications',
                    [
                        'file' => new Stream($rootPath . '/logs/notifications.log'),
                    ]
                );
            },
        );

    }
}
