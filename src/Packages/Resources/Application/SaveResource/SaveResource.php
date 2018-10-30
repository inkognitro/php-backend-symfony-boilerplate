<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveResources;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class SaveResource implements Command
{
    private $resourceClassName;
    private $attributeValues;

    public function __construct(string $resourceClassName, array $attributeValues)
    {
        $this->resourceClassName = $resourceClassName;
        $this->attributeValues = $attributeValues;
    }

    public function getResourceClassName(): string
    {
        return $this->resourceClassName;
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