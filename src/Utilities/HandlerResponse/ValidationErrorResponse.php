<?php declare(strict_types=1);

namespace App\Utilities\HandlerResponse;

use App\Utilities\Validation\Messages\MessageBag;

final class ValidationErrorResponse implements Error
{
    private $errors;
    private $warnings;

    public function __construct(MessageBag $errors, MessageBag $warnings)
    {
        $this->errors = $errors;
        $this->warnings = $warnings;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
}