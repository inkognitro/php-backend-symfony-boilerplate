<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Domain\Event\Event;
use App\Packages\Common\Infrastructure\DbalConnection;
use LogicException;

final class UserEventProjector
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(Event $event): void
    {
        throw new LogicException('Event "' . get_class($event) . '" not supported!');
    }
}