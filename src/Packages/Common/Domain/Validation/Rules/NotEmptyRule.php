<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Validation\Rules;

use App\Packages\Common\Domain\Validation\Messages\Message;
use App\Packages\Common\Domain\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Domain\Validation\Messages\MustNotBeEmptyMessage;

final class NotEmptyRule
{
    /** @param $text mixed */
    public static function findError($text): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen(trim($text)) === 0) {
            return new MustNotBeEmptyMessage();
        }
        return null;
    }
}