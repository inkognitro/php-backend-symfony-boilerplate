<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Command\SaveResources;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class SaveResources implements Command
{
    private $multiplePropertyValues;

    /** @param $multiplePropertyValues PropertyValues[] */
    public function __construct(array $multiplePropertyValues)
    {
        $this->multiplePropertyValues = $multiplePropertyValues;
    }

    public function getMultiplePropertyValues(): array
    {
        return $this->multiplePropertyValues;
    }

    public function getPermission(): ?Permission
    {
        return null;
    }
}