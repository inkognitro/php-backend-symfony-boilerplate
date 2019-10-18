<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;

interface Resource
{
    public static function getTypeId(): ResourceTypeId;
}