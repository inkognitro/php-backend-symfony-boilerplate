<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Rules;

use App\Packages\Common\Utilities\Validation\Messages\Message;
use App\Packages\Common\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeLongerThanMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotBeShorterThanMessage;
use App\Packages\Common\Utilities\Validation\Messages\MustNotContainLineBreaks;
use App\Packages\Common\Utilities\Validation\Messages\OnlyDefinedCharsAreAllowed;

final class TextRule implements Rule
{
    private $minLength;
    private $maxLength;
    private $canHaveLineBreaks;
    private $mustTrimTextForValidation;
    private $allowedCharsRegex;

    private function __construct(
        ?int $minLength,
        ?int $maxLength,
        bool $canHaveLineBreaks,
        bool $mustTrimTextForValidation,
        ?string $allowedCharsRegex
    )
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->canHaveLineBreaks = $canHaveLineBreaks;
        $this->mustTrimTextForValidation = $mustTrimTextForValidation;
        $this->allowedCharsRegex = $allowedCharsRegex;
    }

    public static function create(): self
    {
        return new self(null, null, false, true, null);
    }

    private function changeFromArray(array $data): self
    {
        return new self(
            ($data['minLength'] ?? $this->minLength),
            ($data['maxLength'] ?? $this->maxLength),
            ($data['canHaveLineBreaks'] ?? $this->canHaveLineBreaks),
            ($data['mustTrimTextForValidation'] ?? $this->mustTrimTextForValidation),
            ($data['allowedCharsRegex'] ?? $this->allowedCharsRegex)
        );
    }

    public function setMinLength(int $minLength): self
    {
        return $this->changeFromArray(['minLength' => $minLength]);
    }

    public function setMaxLength(int $maxLength): self
    {
        return $this->changeFromArray(['maxLength' => $maxLength]);
    }

    public function setAllowedCharsRegex(string $regex): self
    {
        return $this->changeFromArray(['allowedCharsRegex' => $regex]);
    }

    /** @param $text mixed */
    public function findError($text): ?Message
    {
        if (!is_string($text)) {
            return new MustBeAStringMessage();
        }

        $textToCheck = ($this->mustTrimTextForValidation ? trim($text) : $text);

        if ($this->minLength && strlen($textToCheck) === 0) {
            return new MustNotBeEmptyMessage();
        }

        if($this->canHaveLineBreaks === false && strstr($text, PHP_EOL)) {
            return new MustNotContainLineBreaks();
        }

        if($this->allowedCharsRegex && !preg_match($this->allowedCharsRegex, $text)) {
            return new OnlyDefinedCharsAreAllowed($this->allowedCharsRegex);
        }

        if ($this->minLength && strlen($textToCheck) < $this->minLength) {
            return new MustNotBeShorterThanMessage($this->minLength);
        }

        if ($this->maxLength !== null && strlen($textToCheck) > $this->maxLength) {
            return new MustNotBeLongerThanMessage($this->maxLength);
        }

        return null;
    }
}