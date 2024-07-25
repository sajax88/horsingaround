<?php
declare(strict_types=1);


namespace App\Api\Controllers;

use App\Api\Validators\LoginValidator;
use App\Main\Services\AuthenticationService;
use Phalcon\Messages\Message;

class AuthController extends BaseApiController {
    /**
     * Authenticates the user and generates JWT
     */
    public function indexAction(): void {
        if ($this->request->getMethod() !== "POST") {
            $this->response->setStatusCode(405);
            return;
        }

        $validator     = new LoginValidator();
        $errorMessages = $validator->validate($this->request->getJsonRawBody(true));
        if (count($errorMessages)) {
            $this->response->setPayloadErrors($errorMessages);
            return;
        }

        /** @var AuthenticationService $authService */
        $authService = $this->container->get('auth');
        $user = $authService->getUser($validator->getValue('email'), $validator->getValue('password'));

        if ($user) {
            $this->response->setPayloadSuccess(['token' => $authService->getToken($user)]);
        } else {
            $this->response->setPayloadErrors(['Incorrect credentials']);
        }
    }
}
