<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Resources\Job;

use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\ResourceId;
use App\Packages\Common\Application\Resources\UpdatedAt;

final class Job extends AbstractResource
{
    private $id;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        JobId $id,
        ?CreatedAt $createdAt,
        ?UpdatedAt $updatedAt
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getResourceId(): ResourceId
    {
        return $this->id;
    }

    public function getId(): JobId
    {
        return $this->id;
    }

    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }
}