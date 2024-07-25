<?php

declare(strict_types=1);

namespace App\Plugins;

use Exception;
use Phalcon\Di\Di;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

class ApiPageNotFoundPlugin extends Injectable {
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception): bool {
        $notFoundExceptionCodes = [
            DispatcherException::EXCEPTION_HANDLER_NOT_FOUND,
            DispatcherException::EXCEPTION_ACTION_NOT_FOUND,
        ];

        // For API we just return 404 without redirecting anywhere
        if (
            $exception instanceof DispatcherException
            && in_array($exception->getCode(), $notFoundExceptionCodes)
        ) {
            /** @var Response $response */
            $response = Di::getDefault()->getShared('response');
            $response->setStatusCode(404);
            return false;
        }

        return !$event->isStopped();
    }
}
