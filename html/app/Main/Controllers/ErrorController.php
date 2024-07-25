<?php
declare(strict_types=1);


namespace App\Main\Controllers;

use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{
    public function pageNotFoundAction(): void
    {
        print('404 Page not found!');
        exit;
    }

    public function accessRestrictedAction(): void
    {
        print('401 You are not allowed to access this page!');
        exit;
    }
}
