<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;

interface Projection
{
    public function project(AbstractEvent $event): void;
}