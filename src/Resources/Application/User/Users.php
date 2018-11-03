<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class Users
{
    private $users;

    /** @param $users User[] */
    private function __construct(array $users)
    {
        $this->users = $users;
    }

    /** @return User[] */
    public function toCollection(): array
    {
        return $this->users;
    }

    public function toCommandData(): array
    {
        $data = [];
        foreach($this->users as $user) {
            $data[] = $user->toCommandData();
        }
        return $data;
    }
}