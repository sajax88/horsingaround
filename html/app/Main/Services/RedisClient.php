<?php

declare(strict_types=1);

namespace App\Main\Services;

use Predis\Client;

/**
 * Shortcuts for queue commands that we use in our app
 */
class RedisClient extends Client {
    private const QUEUE_NOTIFICATIONS = 'notifications';

    public function pushToNotificationsQueue(array $values): void {
        $this->lpush(self::QUEUE_NOTIFICATIONS, $values);
    }

    public function popMultipleFromNotificationsQueue(int $limit): array {
        $elements = $this->lrange(self::QUEUE_NOTIFICATIONS, -$limit, $limit);
        $elementsCount = count($elements);
        // Clear these elements
        $this->ltrim(self::QUEUE_NOTIFICATIONS, $elementsCount, -$elementsCount);
        return $elements;
    }
}
