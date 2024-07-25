<?php

declare(strict_types=1);

namespace App\Main\Services;

use Amp\Http\Client\HttpException;
use Amp\Http\Client\Request;
use Phalcon\Di\DiInterface;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Logger\LoggerInterface;
use Amp\Http\Client\HttpClientBuilder;
use Amp\Future;
use function Amp\async;

class TelegramNotificationService implements InjectionAwareInterface, NotificationServiceInterface {
    const string ACTION_SEND_MESSAGE = 'sendMessage';

    private string $apiUrl;

    private string $adminTgChatId;

    protected DiInterface $container;


    public function __construct(string $apiUrl, string $adminTgChatId) {
        $this->apiUrl = $apiUrl;
        $this->adminTgChatId = $adminTgChatId;
    }

    public function setDi(DiInterface $container): void {
        $this->container = $container;
    }

    public function getDi(): DiInterface {
        return $this->container;
    }

    public function sendNotificationsToAdmin(array $messages): void {
        $this->logSendingStart(count($messages));

        $notificationsMap = [];
        foreach ($messages as $id => $message) {
            $data =  [
                'text'    => $message,
                'chat_id' => $this->adminTgChatId,
            ];
            $request = new Request($this->apiUrl . self::ACTION_SEND_MESSAGE, "POST", json_encode($data));
            $request->setHeader('Content-Type', 'application/json');

            $notificationsMap[$id] = [
                'data' => $data,
                'request' => $request,
            ];
        }
        $this->sendAsyncRequestsToTelegramApi($notificationsMap);
    }

    private function sendAsyncRequestsToTelegramApi(array $notificationsMap): void {
        $client = HttpClientBuilder::buildDefault();
        $requestHandler = static function (Request $request) use ($client): string {
            $response = $client->request($request);
            return $response->getBody()->buffer();
        };

        try {
            $futures = [];
            foreach ($notificationsMap as $notificationId => $notification) {
                $futures[$notificationId] = async(fn () => $requestHandler($notification['request']));
            }
            $results = Future\await($futures);

            foreach ($results as $notificationId => $body) {
                $result = json_decode($body, true);
                $data = $notificationsMap[$notificationId]['data'];
                if (isset($result["ok"]) && $result["ok"]) {
                    $this->logSuccess($result["result"]["message_id"], $data);
                } else {
                    // TODO: collect, resend
                    $this->logError($result["description"] ?? "Unknown error", $data);
                }
            }

        } catch (HttpException $error) {
            $this->logError("HttpException: " . $error);
        }
    }

    private function logSendingStart(int $messagesCount): void {
        $logMessage = "Sending $messagesCount messages to admin";
        $this->getLogger()->info($logMessage);
    }

    private function logSuccess(int $messageId, array $data): void {
        $logMessage = "Telegram message sent, ID: " . $messageId . "\nChat: " . $data["chat_id"] . "\nMessage: " . $data["text"];
        $this->getLogger()->info($logMessage);
    }

    private function logError(string $errorMessage, ?array $data=null): void {
        if ($data) {
            $errorMessage .= "\nChat: " . $data["chat_id"] . "\nMessage: " . $data["text"];
        }
        $this->getLogger()->error($errorMessage);
    }

    private function getLogger(): LoggerInterface {
        return $this->getDi()->getShared('notifications_logger');
    }
}
