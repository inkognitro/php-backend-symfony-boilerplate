<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\AuditLog;

interface Projection
{
    public function when(AuditLogEvent $event): void;
}