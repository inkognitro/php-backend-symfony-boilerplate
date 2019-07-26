<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class Users
{
    private $users;

    /** @param $users User[] */
    private function __construct(array $users)
    {
        $this->users;
    }

    public static function fromUsersArray(array $users): self
    {
        return new self($users);
    }

    /** @return User[] */
    public function toArray(): array
    {
        return $this->users;
    }

    public function findFirst(): ?User
    {
        if ($this->users[0]) {
            return $this->users[0];
        }
        return null;
    }
}