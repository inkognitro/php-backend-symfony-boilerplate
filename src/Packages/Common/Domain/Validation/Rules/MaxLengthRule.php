<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Validation\Rules;

use App\Packages\Common\Domain\Validation\Messages\Message;
use App\Packages\Common\Domain\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Domain\Validation\Messages\MustNotBeLongerThanMessage;

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