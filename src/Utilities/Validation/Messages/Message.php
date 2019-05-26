<?php declare(strict_types=1);

namespace App\Utilities\Validation\Messages;

interface Message
{
    public function getCode(): string;
    public function getMessage(): string;
    public function getPlaceholders(): array;
}