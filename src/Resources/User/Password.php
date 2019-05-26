<?php declare(strict_types=1);

namespace App\Resources\User;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Rules\MaxLengthRule;
use App\Utilities\Validation\Rules\MinLengthRule;
use App\Resources\Attribute;

final class Password implements Attribute
{
    private $passwordHash;

    public static function getKey(): string
    {
        return 'user.passwordHash';
    }

    /** @param $password mixed */
    public static function findFormatError($password): ?Message
    {
        $minLength = 4;
        $error = MinLengthRule::findError($password, $minLength);
        if($error) {
            return $error;
        }
        $maxLength = 40;
        return MaxLengthRule::findError($password, $maxLength);
    }

    private function __construct(string $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public static function fromString(string $password): self
    {
        $algorithm = PASSWORD_BCRYPT;
        $algorithmOptions = ['cost' => 8];
        $hash = password_hash($password, $algorithm, $algorithmOptions);
        return new self($hash);
    }

    public static function fromHash(string $passwordHash): self
    {
        return new self($passwordHash);
    }

    public function toHash(): string
    {
        return $this->passwordHash;
    }

    public function isSame(self $password): bool
    {
        return hash_equals($this->passwordHash, $password->toHash());
    }
}