<?php

declare(strict_types=1);

namespace App\Main\Models;

use App\Main\Services\RedisClient;
use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

class Requests extends Model {
    public function beforeCreate() {
        $this->created_at = new RawValue('default');
    }

    public ?int $id = null;

    public string $full_name;

    public string $description = "";

    public string|RawValue $created_at;

    public function afterCreate()
    {
        // Add record to Redis queue, notifications will be sent by cron
        /** @var RedisClient $redisClient */
        $redisClient = $this->container->get('redis');
        $redisClient->pushToNotificationsQueue([$this->id]);
    }
}
