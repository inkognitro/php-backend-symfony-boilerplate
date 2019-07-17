<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Resources\Application\Resource;
use App\Resources\Application\User\Attributes\UserId;

final class User implements Resource
{
    private $id;

    public function __construct(?UserId $id)
    {
        $this->id;
    }

    public function getId(): ?UserId
    {
        return $this->id;
    }
}