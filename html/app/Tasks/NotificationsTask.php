<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Main\Services\NotificationServiceInterface;
use App\Main\Services\RedisClient;
use Phalcon\Cli\Task;

/**
 * Gets pending notifications from Redis queue and sends them
 */
class NotificationsTask extends Task
{
    public function mainAction()
    {
        echo 'Start sending notifications.' . PHP_EOL;

        /** @var NotificationServiceInterface $notificationsService */
        $notificationsService = $this->container->getShared('notifications');

        // Pop notifications from queue, no more than $limit at a time
        $limit = 50;
        /** @var RedisClient $redisClient */
        $redisClient = $this->container->get('redis');
        // TODO: here we remove the ids from queue, should add re-scheduling in case of error
        $notificationIds = $redisClient->popMultipleFromNotificationsQueue($limit);
        $messages = [];
        foreach ($notificationIds as $notificationId) {
            $messages[$notificationId] = "New request #$notificationId created";
        }
        // TODO: we should really send just one notification in this case, but for the sake of playing around with async stuff...
        $notificationsService->sendNotificationsToAdmin($messages);

        echo "Sent " . count($notificationIds) . " notifications." . PHP_EOL;
    }
}


