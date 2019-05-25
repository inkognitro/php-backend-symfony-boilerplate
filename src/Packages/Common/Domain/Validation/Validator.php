<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Validation\Messages\MessageBag;

abstract class Validator
{
    protected $errors;
    protected $warnings;

    public function __construct()
    {
        $this->errors = new MessageBag();
        $this->warnings = new MessageBag();
    }

    public function reset(): void
    {
        $this->errors = new MessageBag();
        $this->warnings = new MessageBag();
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }

    public function hasErrors(): bool
    {
        return ($this->getErrors()->getCount() !== 0);
    }
}