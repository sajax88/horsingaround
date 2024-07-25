<?php

declare(strict_types=1);

namespace App\Api\Http;

use Phalcon\Http\Response;
use Phalcon\Messages\MessageInterface;
use Phalcon\Messages\Messages;

/**
 * JSON encode the response before sending it
 */
class ApiResponse extends Response {
    public function setPayloadSuccess($content = []): Response {
        $this->setJsonContent(['data' => $content]);
        return $this;
    }

    /**
     * @param MessageInterface[]|Messages|string[] $errors
     */
    public function setPayloadErrors($errors): Response {
        $data = [];
        foreach ($errors as $error) {
            if (is_string($error)) {
                $data[] = $error;
            } else {
                // Validation errors
                $data[] = $error->getMessage();
            }
        }
        $this->setJsonContent(['errors' => $data]);
        return $this;
    }
}
