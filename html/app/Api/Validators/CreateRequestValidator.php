<?php
declare(strict_types=1);


namespace App\Api\Validators;

use App\Main\Models\Requests;
use Phalcon\Filter\Filter;
use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Validator\StringLength;

class CreateRequestValidator extends Validation {
    /**
     * @see Requests
     */
    public function initialize(): void {
        $stringFilters = [
            Filter::FILTER_STRING,
            Filter::FILTER_TRIM,
            Filter::FILTER_STRIPTAGS,
        ];
        $this->setFilters('full_name', $stringFilters);
        $this->setFilters('description', $stringFilters);

        $this->add(
            "full_name",
            new StringLength(
                [
                    "max" => 128,
                    "min" => 2,
                ]
            )
        );
    }
}
