<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class Users
{
    private $users;

    /** @param $users User[]*/
    public function __construct(array $users)
    {
        $this->users;
    }

    /** @return User[]*/
    public function toArray(): array
    {
        return $this->users;
    }
}