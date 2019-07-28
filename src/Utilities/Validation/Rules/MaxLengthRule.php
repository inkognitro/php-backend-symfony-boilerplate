<?php declare(strict_types=1);

namespace App\Utilities\Validation\Rules;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Utilities\Validation\Messages\MustNotBeLongerThanMessage;

final class MaxLengthRule implements Rule
{
    private $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /** @param $text mixed */
    public function findError(string $text): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen($text) > $this->maxLength) {
            return new MustNotBeLongerThanMessage($this->maxLength);
        }
        return null;
    }
}