<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Role;

final class Role
{
    public const KEY = 'role';

    private $id;
    private $name;

    public function __construct(RoleId $id, RoleName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): RoleId
    {
        return $this->id;
    }

    public function getName(): RoleName
    {
        return $this->name;
    }
}