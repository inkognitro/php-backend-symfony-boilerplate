<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Messages;

final class OnlyDefinedCharsAreAllowed implements Message
{
    private $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function getCode(): string
    {
        return 'onlyDefinedCharsAreAllowed';
    }

    public function getMessage(): string
    {
        return 'only the chars %regex% are allowed';
    }

    public function getPlaceholders(): array
    {
        return [
            'regex' => $this->regex,
        ];
    }
}