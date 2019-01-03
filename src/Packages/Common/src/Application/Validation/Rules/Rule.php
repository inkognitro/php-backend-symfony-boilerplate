<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Rules;

use App\Packages\Common\Application\Validation\Messages\Message;

interface Rule
{
    /** @param $data mixed */
    public static function getMessageFromValidation($data): ?Message;
}