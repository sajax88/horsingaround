<?php

declare(strict_types=1);

namespace App\Providers;

use App\Main\Services\TelegramNotificationService;
use Phalcon\Config\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Currently we're using Telegram for sending notifications
 */
class NotificationsProvider implements ServiceProviderInterface {
    public function register(DiInterface $di): void {
        /** @var Config $config */
        $config = $di->getShared('config');
        $tgConfig = $config->get('telegram');

        $di->setShared(
            'notifications',
            function () use ($tgConfig) {
                return new TelegramNotificationService(
                    $tgConfig->api_url,
                    $tgConfig->admin_tg_chat_id
                );
            }
        );
    }
}
