<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Rules;

use App\Packages\Common\Application\Validation\Messages\Message;
use App\Packages\Common\Application\Validation\Messages\MustNotBeEmptyMessage;

final class NotEmptyRule implements Rule
{
    public static function getMessageFromValidation($data): ?Message
    {
        $data = (string)$data;
        if(strlen(trim($data)) === 0) {
            return new MustNotBeEmptyMessage();
        }
        return null;
    }
}