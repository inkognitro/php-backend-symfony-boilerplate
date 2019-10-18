<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\HandlerResponse;

use App\Packages\Common\Application\Utilities\Validation\Messages\MessageBag;
use App\Packages\Common\Application\Utilities\Validation\ValidationResult;

final class ValidationErrorResponse implements Error
{
    private $fieldErrors;

    private function __construct(MessageBag $fieldErrors)
    {
        $this->fieldErrors = $fieldErrors;
    }

    public static function fromValidationResult(ValidationResult $validationResult): self
    {
        return new self($validationResult->getFieldErrors());
    }

    public function getFieldErrors(): MessageBag
    {
        return $this->fieldErrors;
    }
}