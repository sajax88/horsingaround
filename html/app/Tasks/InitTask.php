<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Main\Models\Users;
use Phalcon\Cli\Task;
use Phalcon\Encryption\Security;

class InitTask extends Task
{
    public function mainAction()
    {
        echo 'Initializing the app. Creating demo user.' . PHP_EOL;

        $demoEmail = 'demo@demo.test';

        if (Users::findFirstByEmail($demoEmail)) {
            echo 'Demo user already exists.' . PHP_EOL;
            return;
        }

        $security = new Security();
        $demoUser = new Users();
        $demoUser->email = $demoEmail;
        $demoUser->password = $security->hash('demo');
        $demoUser->full_name = 'Demo';
        $demoUser->save();
    }
}
