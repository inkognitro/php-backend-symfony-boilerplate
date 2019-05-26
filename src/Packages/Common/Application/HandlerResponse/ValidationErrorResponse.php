<?php declare(strict_types=1);

namespace App\Packages\Common\Application\HandlerResponse;

use App\Utilities\Validation\Messages\MessageBag;

final class ValidationErrorResponse implements Response
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