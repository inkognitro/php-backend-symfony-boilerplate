<?php declare(strict_types=1);

namespace App\Resources\Application;

use App\Packages\Common\Application\Validation\Messages\MessageBag;

abstract class AbstractValidator
{
    protected $errors;
    protected $warnings;

    public function __construct()
    {
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
    }

    public function validate(array $data): void
    {
        $this->validateData($data);
    }

    protected abstract function validateData(array $dataToValidate): void;

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
}