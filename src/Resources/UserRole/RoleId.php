<?php declare(strict_types=1);

namespace App\Resources\UserRole;

use App\Utilities\Validation\Messages\Message;
use App\Utilities\Validation\Rules\RequiredStringRule;
use App\Resources\Attribute;

final class RoleId implements Attribute
{
    private $roleId;

    public static function getKey(): string
    {
        return 'userRole.id';
    }

    /** @param $roleId mixed */
    public static function findFormatError($roleId): ?Message
    {
        return RequiredStringRule::findError($roleId);
    }

    private function __construct(string $roleId)
    {
        $this->roleId = $roleId;
    }

    public static function fromString(string $roleId): self
    {
        return new self($roleId);
    }

    public function toString(): string
    {
        return $this->roleId;
    }
}