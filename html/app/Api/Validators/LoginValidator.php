<?php
declare(strict_types=1);


namespace App\Api\Validators;

use App\Main\Models\Requests;
use Phalcon\Filter\Filter;
use Phalcon\Filter\Validation;

class LoginValidator extends Validation {
    /**
     * @see Requests
     */
    public function initialize(): void {
        $stringFilters = [
            Filter::FILTER_STRING,
            Filter::FILTER_TRIM,
            Filter::FILTER_STRIPTAGS,
        ];
        $this->setFilters('login', $stringFilters);
        $this->setFilters('password', $stringFilters);
    }
}
