<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveUser;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class SaveUser implements Command
{
    private $attributeValues;

    public function __construct(array $attributeValues)
    {
        $this->attributeValues = $attributeValues;
    }

    public function getAttributeValues(): array
    {
        return $this->attributeValues;
    }

    public function getPermission(): ?Permission
    {
        return null;
    }
}