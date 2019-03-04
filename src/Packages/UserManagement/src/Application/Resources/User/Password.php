<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

final class Password
{
    public const KEY = User::KEY . '.password';
    private $passwordHash;

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