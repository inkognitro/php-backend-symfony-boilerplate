<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\Validation\Messages\MessageBag;

abstract class Validator
{
    protected $errors;
    protected $warnings;

    protected function __construct()
    {
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return ($this->errors->getCount() !== 0);
    }
}