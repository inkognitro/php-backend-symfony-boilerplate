<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustNotBeShorterThanMessage;

final class MinLengthRule
{
    /** @param $text mixed */
    public static function findError(string $text, int $minLength): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen($text) < $minLength) {
            return new MustNotBeShorterThanMessage($minLength);
        }
        return null;
    }
}