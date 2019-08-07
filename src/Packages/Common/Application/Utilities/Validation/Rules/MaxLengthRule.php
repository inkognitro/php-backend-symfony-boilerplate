<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Rules;

use App\Packages\Common\Utilities\Validation\Messages\Message;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeLongerThanMessage;

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