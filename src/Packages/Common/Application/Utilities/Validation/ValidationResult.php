<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation;

use App\Packages\Common\Application\Utilities\Validation\Messages\Message;
use App\Packages\Common\Application\Utilities\Validation\Messages\MessageBag;

final class ValidationResult
{
    protected $fieldErrors;

    private function __construct(MessageBag $fieldErrors)
    {
        $this->fieldErrors = $fieldErrors;
    }

    public function merge(self $that): self
    {
        return new self($this->getFieldErrors()->merge($that->getFieldErrors()));
    }

    public static function create(): self
    {
        return new self(MessageBag::create());
    }

    public function isValid(): bool
    {
        return $this->fieldErrors->isEmpty();
    }

    public function getFieldErrors(): MessageBag
    {
        return $this->fieldErrors;
    }

    public function addFieldErrorMessage(string $key, Message $message): self
    {
        $fieldErrors = $this->fieldErrors->addMessage($key, $message);
        return new self($fieldErrors);
    }
}