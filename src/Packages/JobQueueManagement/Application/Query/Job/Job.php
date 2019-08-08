<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Application\Query\Job;

use App\Packages\Common\Application\Query\Resource;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;

final class Job implements Resource
{
    public static function getTypeId(): ResourceTypeId
    {
        return ResourceTypeId::queueJob();
    }
}