<?php

declare(strict_types=1);

namespace App\Providers;

use App\Main\Services\AuthenticationService;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class AuthProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared(
            'auth',
            function () {
                return new AuthenticationService();
            }
        );
    }
}
