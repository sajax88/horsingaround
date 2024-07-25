<?php
declare(strict_types=1);


namespace App\Api\Controllers;

use App\Api\Validators\CreateRequestValidator;
use App\Main\Models\Requests;

class CreateRequestController extends BaseApiController {
    /**
     * Accepts POST from the remote website
     * and creates a request in our system.
     */
    public function indexAction(): void {
        if ($this->request->getMethod() !== "POST") {
            $this->response->setStatusCode(405);
            return;
        }

        $validator     = new CreateRequestValidator();
        $errorMessages = $validator->validate($this->request->getJsonRawBody(true));
        if (count($errorMessages)) {
            $this->response->setPayloadErrors($errorMessages);
            return;
        }

        /**
         * Saving the model triggers notifications
         * @see Requests::afterCreate()
         */
        $newRequest = new Requests($validator->getData());
        if (!$newRequest->save()) {
            $this->response->setPayloadErrors($newRequest->getMessages());
            return;
        }

        $this->response->setPayloadSuccess(['id' => $newRequest->id]);
    }
}
