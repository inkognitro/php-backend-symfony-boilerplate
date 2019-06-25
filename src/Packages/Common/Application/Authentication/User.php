<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authentication;

use App\Resources\Role\RoleId;
use App\Resources\User\UserId;

final class User
{
    private $id;
    private $roleId;

    public function __construct(UserId $id, RoleId $roleId)
    {
        $this->id = $id;
        $this->roleId = $roleId;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }
}