<?php

declare(strict_types=1);

namespace App\Main\Models;

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

class Users extends Model
{
    public function beforeCreate()
    {
        $this->created_at = new RawValue('default');
    }

    public ?int $id = null;

    public string $email;

    public string $password;

    public string $full_name;

    public string|RawValue $created_at;

    public bool $is_active = true;
}
