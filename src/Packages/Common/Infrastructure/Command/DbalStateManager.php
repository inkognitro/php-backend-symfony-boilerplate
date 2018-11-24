<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\Command;

use App\Packages\Common\Application\StateManager;

final class DbalStateManager implements StateManager
{
    public function __construct()
    {
        /**
         * todo: Use EntityManager of Doctrine for transaction methods below:
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/transactions-and-concurrency.html#approach-2-explicitly
         */
    }

    public function noticeChanges(): void
    {
        // TODO: start transaction via EntityManager
    }

    public function saveNoticedChanges(): void
    {
        // TODO: execute flush method of EntityManager
    }

    public function discardNoticedChanges(): void
    {
        // TODO: do nothing?
    }
}