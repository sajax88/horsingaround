<?php

declare(strict_types=1);

namespace App\Plugins;

use App\Api\Http\ApiResponse;
use App\Main\Services\AuthenticationService;
use HttpRequest;
use Phalcon\Di\Injectable;
use Phalcon\Encryption\Security\JWT\Exceptions\ValidatorException;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

/**
 * Checks if JWT is valid.
 * Currently every authenticated user can add requests, so no ACL here.
 */
class TokenVerificationPlugin extends Injectable {

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        if ($dispatcher->getControllerName() === 'auth') {
            return true;
        }

        /** @var HttpRequest $request */
        $request = $this->request;
        /** @var AuthenticationService $authService */
        $authService = $this->auth;

        $token = str_replace('Bearer ', '', $request->getHeader('Authorization'));
        $validationErrors = $authService->validateToken($token);

        if ($validationErrors) {
            /** @var ApiResponse $response */
            $response = $this->response;
            $response
                ->setPayloadErrors($validationErrors)
                ->setStatusCode(401);
            return false;
        }
        return true;
    }
}
