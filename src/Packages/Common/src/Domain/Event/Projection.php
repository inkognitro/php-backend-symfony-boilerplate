<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

interface Projection
{
    public function project(Event $event): void;
}