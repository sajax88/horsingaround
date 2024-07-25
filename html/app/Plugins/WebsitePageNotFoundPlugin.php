<?php

declare(strict_types=1);

namespace App\Plugins;

use Exception;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

class WebsitePageNotFoundPlugin extends Injectable {
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception): bool {
        $notFoundExceptionCodes = [
            DispatcherException::EXCEPTION_HANDLER_NOT_FOUND,
            DispatcherException::EXCEPTION_ACTION_NOT_FOUND,
        ];

        if (
            $exception instanceof DispatcherException
            && in_array($exception->getCode(), $notFoundExceptionCodes)
            && $dispatcher->getControllerName() != 'error'
        ) {
            $dispatcher->forward([
                'controller' => 'error',
                'action'     => 'pageNotFound',
            ]);
            return false;
        }

        return !$event->isStopped();
    }
}
