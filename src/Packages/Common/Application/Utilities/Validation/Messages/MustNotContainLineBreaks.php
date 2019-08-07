<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Messages;

final class MustNotContainLineBreaks implements Message
{
    public function getCode(): string
    {
        return 'mustNotContainLineBreaks';
    }

    public function getMessage(): string
    {
        return 'must not contain line breaks';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}