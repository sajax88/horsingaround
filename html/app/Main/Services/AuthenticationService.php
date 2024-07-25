<?php

declare(strict_types=1);

namespace App\Main\Services;

use App\Main\Models\Users;
use http\Client\Curl\User;
use Phalcon\Config\Config;
use Phalcon\Di\DiInterface;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Encryption\Security;
use Phalcon\Encryption\Security\JWT\Builder;
use Phalcon\Encryption\Security\JWT\Exceptions\ValidatorException;
use Phalcon\Encryption\Security\JWT\Signer\Hmac;
use Phalcon\Encryption\Security\JWT\Token\Parser;
use Phalcon\Encryption\Security\JWT\Token\Token;
use Phalcon\Encryption\Security\JWT\Validator;

/**
 * Finds user by email and password (used both for main website and API).
 * Builds JWT for API users.
 */
class AuthenticationService implements InjectionAwareInterface {
    protected DiInterface $container;

    public function setDi(DiInterface $container): void {
        $this->container = $container;
    }

    public function getDi(): DiInterface {
        return $this->container;
    }

    public function getUser($email, $password): ?Users {
        $security = new Security();
        /** @var Users $user */
        $user = Users::findFirst(
            [
                "conditions" =>
                    "email = :email: "
                    . "AND is_active = 1",
                'bind'       => [
                    'email' => $email,
                ],
            ]
        );

        if ($user && $security->checkHash($password, $user->password)) {
            return $user;
        }
        return null;
    }


    public function getToken(Users $user): string {
        return $this->getTokenBuilder($user)->getToken();
    }

    public function validateToken(string $token): array {
        $parser = new Parser();
        try {
            $tokenObject = $parser->parse($token);
        } catch (\InvalidArgumentException) {
            return ['Invalid token!'];
        }

        $user = Users::findFirst(
            [
                "conditions" =>
                    "id = :id: "
                    . "AND is_active = 1",
                'bind'       => [
                    'id' => (int) $tokenObject->getClaims()->get('sub'),
                ],
            ]
        );
        if (!$user) {
            return ["Invalid token!"];
        }

        $tokenBuilder = $this->getTokenBuilder($user);
        $validator    = new Validator($tokenBuilder, 10);
        try {
            return $tokenObject->validate($validator);
        } catch (ValidatorException) {
            return $validator->getErrors();
        }
    }

    private function getTokenBuilder(Users $user): Token {
        /** @var Config $config */
        $config = $this->getDi()->getShared('config');

        $signer  = new Hmac();
        $builder = new Builder($signer);

        $now       = new \DateTimeImmutable();
        $issued    = $now->getTimestamp();
        $notBefore = $now->modify('-1 minute')->getTimestamp();
        $expires   = $now->modify('+1 day')->getTimestamp();

        $builder
            ->setAudience('http://remote.website')
            ->setContentType('application/json')
            ->setExpirationTime($expires)
            ->setIssuedAt($issued)
            ->setIssuer($config->get('base_url'))
            ->setSubject((string) $user->id)
            ->setNotBefore($notBefore)
            ->setPassphrase($config->get('jwt_passphrase'));

        return $builder->getToken();
    }
}
