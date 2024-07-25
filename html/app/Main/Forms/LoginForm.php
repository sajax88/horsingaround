<?php

declare(strict_types=1);

namespace App\Main\Forms;

use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class LoginForm extends Form
{
    public function initialize()
    {
        $this->addEmailField();
        $this->addPasswordField();
    }

    private function addEmailField() {
        $email = new Text('email');
        $email->setLabel('Email');
        $email->setFilters(['striptags', 'string']);
        $email->addValidators([
            new PresenceOf(['message' => 'Email is required']),
        ]);
        $this->add($email);
    }

    private function addPasswordField() {
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators([
            new PresenceOf(['message' => 'Password is required']),
        ]);

        $this->add($password);
    }
}
