<?php

declare(strict_types=1);

namespace App\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        echo 'Main task that does absolutely nothing.' . PHP_EOL;
    }
}
