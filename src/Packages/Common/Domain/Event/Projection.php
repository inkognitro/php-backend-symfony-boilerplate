<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

interface Projection
{
    public function when(AuditLogEvent $event): void;
}