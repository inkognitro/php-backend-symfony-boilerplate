<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Event\AbstractAuditLogEvent;

interface Projection
{
    public function when(AbstractAuditLogEvent $event): void;
}