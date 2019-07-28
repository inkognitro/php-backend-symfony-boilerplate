<?php declare(strict_types=1);

namespace App\Utilities\Validation;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MessageBag;

final class ValidationResult
{
    protected $fieldErrors;

    private function __construct(MessageBag $fieldErrors)
    {
        $this->fieldErrors = $fieldErrors;
    }

    public static function create(): self
    {
        return new self(MessageBag::create());
    }

    public function isValid(): bool
    {
        return $this->fieldErrors->isEmpty();
    }

    public function addFieldErrorMessage(string $key, Message $message): self
    {
        $fieldErrors = $this->fieldErrors->addMessage($key, $message);
        return new self($fieldErrors);
    }
}