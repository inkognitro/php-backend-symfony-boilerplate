<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Domain\Event\Event;
use App\Packages\Common\Domain\Event\Projection;

final class AuditLogProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(Event $event): void
    {
        if (!$event->mustBeLogged()) {
            return;
        }

        //todo: project event in audit log table!
    }
}