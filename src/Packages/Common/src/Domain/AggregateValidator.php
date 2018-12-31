<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\Validation\Messages\MessageBag;

abstract class AggregateValidator
{
    protected $errors;
    protected $warnings;

    protected function __construct()
    {
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
    }

    public function validate(array $data): void
    {
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
        $this->validateData($data);
    }

    protected abstract function validateData(array $resourceData): void;

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