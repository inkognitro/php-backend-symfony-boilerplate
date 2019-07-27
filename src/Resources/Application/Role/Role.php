<?php declare(strict_types=1);

namespace App\Resources\Application\Role;

use App\Resources\Application\Resource;
use App\Resources\Application\Role\Attributes\RoleId;

final class Role implements Resource
{
    private $id;

    public function __construct(?RoleId $id)
    {
        $this->id = $id;
    }

    public function getId(): ?RoleId
    {
        return $this->id;
    }
}