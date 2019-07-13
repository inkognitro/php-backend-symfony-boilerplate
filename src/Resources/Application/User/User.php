<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class User
{
    private $id;

    public function __construct(?UserId $id)
    {
        $this->id;
    }

    public function getId(): UserId
    {
        return $this->id;
    }
}