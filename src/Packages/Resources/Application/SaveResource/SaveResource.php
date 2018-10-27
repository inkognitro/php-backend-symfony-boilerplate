<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveResource;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class SaveResource implements Command
{
    private $resourceType;
    private $resourceData;

    public function __construct(string $resourceType, array $resourceData)
    {
        $this->resourceType = $resourceType;
        $this->resourceData = $resourceData;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getData(): array
    {
        return $this->resourceData;
    }

    public function getPermission(): ?Permission
    {
        return null;
    }
}