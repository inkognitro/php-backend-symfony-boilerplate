<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Domain\Event\Event;
use App\Packages\Common\Domain\Event\Projection;
use App\Packages\Common\Infrastructure\DbalConnection;

final class DbalUserProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(Event $event): void
    {

    }
}