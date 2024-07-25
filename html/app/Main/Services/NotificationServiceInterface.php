<?php

declare(strict_types=1);

namespace App\Main\Services;

/**
 * Any notification service that goes to DI must implement this interface
 */
interface NotificationServiceInterface {
    public function sendNotificationsToAdmin(array $messages): void;
}
