<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Rules;

use App\Packages\Common\Utilities\Validation\Messages\Message;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeShorterThanMessage;

final class MinLengthRule implements Rule
{
    private $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    /** @param $text mixed */
    public function findError(string $text): ?Message
    {
        if(!is_string($text)) {
            return new MustBeAStringMessage();
        }
        if(strlen($text) < $this->minLength) {
            return new MustNotBeShorterThanMessage($this->minLength);
        }
        return null;
    }
}