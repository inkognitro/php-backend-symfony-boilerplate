<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustNotBeLongerThanMessage;

final class MaxLengthRule
{
    /** @param $text mixed */
    public static function findError(string $text, int $maxLength): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen($text) > $maxLength) {
            return new MustNotBeLongerThanMessage($maxLength);
        }
        return null;
    }
}