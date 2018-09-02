<?php declare(strict_types=1);

final class ValidationErrorResponse implements Response
{
    private $errors;

    private function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public static function createFromErrors(array $errors): self
    {
        return new self($errors);
    }

    public function toJson(): string
    {
        return json_encode($this->errors);
    }
}