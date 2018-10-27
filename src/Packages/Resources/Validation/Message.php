<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Messages;

interface Message
{
    public function getMessage(): string;
}