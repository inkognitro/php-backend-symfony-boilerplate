<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

final class Rules
{
    private $rules;

    /** @param $rules Rule[] */
    public function __construct($rules)
    {
        $this->rules = $rules;
    }
}