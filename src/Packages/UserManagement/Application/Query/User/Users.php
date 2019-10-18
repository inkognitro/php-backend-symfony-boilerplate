<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User;

final class Users
{
    private $users;

    /** @param $users User[] */
    private function __construct(array $users)
    {
        $this->users = $users;
    }

    /** @param $users User[] */
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
        if (isset($this->users[0])) {
            return $this->users[0];
        }
        return null;
    }
}