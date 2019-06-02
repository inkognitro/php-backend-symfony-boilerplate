<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

final class Users
{
    private $users;

    /** @param $users User[] */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /** @return User[] */
    public function toArray(): array
    {
        return $this->users;
    }
}