<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Validation\Rules;

use App\Packages\Common\Domain\Validation\Messages\Message;
use App\Packages\Common\Domain\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Domain\Validation\Messages\MustNotBeShorterThanMessage;

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