<?php declare(strict_types=1);

namespace App\Utilities\Validation\Messages;

final class MustNotBeLongerThanMessage implements Message
{
    private $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function getCode(): string
    {
        return 'mustNotBeLonger';
    }

    public function getMessage(): string
    {
        return 'must not be longer than %maxLength%';
    }

    public function getPlaceholders(): array
    {
        return [
            'maxLength' => $this->maxLength,
        ];
    }
}