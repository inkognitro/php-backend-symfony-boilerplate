<?php declare(strict_types=1);

namespace App\Resources\Application;

use App\Packages\Common\Application\Validation\Messages\Messages;

abstract class AbstractValidator
{
    protected $errors;
    protected $warnings;

    public function validate(array $data): void
    {
        $this->warnings = [];
        $this->errors = [];
        $this->validateData($data);
    }

    protected abstract function validateData(array $data): void;

    public function getWarnings(): Messages
    {
        return $this->warnings;
    }

    public function getErrors(): Messages
    {
        return $this->errors;
    }
}