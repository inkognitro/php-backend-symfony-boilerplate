<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Messages;

final class MustNotBeShorterThanMessage implements Message
{
    private $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    public function getCode(): string
    {
        return 'mustNotBeShorter';
    }

    public function getMessage(): string
    {
        return 'must not be shorter than %minLength%';
    }

    public function getPlaceholders(): array
    {
        return [
            'minLength' => $this->minLength,
        ];
    }
}