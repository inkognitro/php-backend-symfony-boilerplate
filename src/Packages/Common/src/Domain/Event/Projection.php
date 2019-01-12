<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

use App\Packages\Common\Application\Resources\Events\Event;

interface Projection
{
    public function project(Event $event): void;
}