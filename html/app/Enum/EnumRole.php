<?php

declare(strict_types=1);

namespace App\Enum;

enum EnumRole: string
{
    case Guest = 'Guest';
    case User = 'User';
}
