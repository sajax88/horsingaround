<?php

declare(strict_types=1);

namespace App\Plugins;

use App\Api\Http\ApiResponse;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

class JsonOutputPlugin extends Injectable {
    public function afterDispatchLoop(Event $event, MvcDispatcher $dispatcher): bool {
        // Since we don't have any view, we need to echo the content somewhere
        // Content-Type header is sent in ApiResponse
        /** @see ApiResponse */
        echo $this->response->getContent();
        return !$event->isStopped();
    }
}
