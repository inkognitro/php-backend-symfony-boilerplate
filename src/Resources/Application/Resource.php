<?php declare(strict_types=1);

namespace App\Resources\Application;

use App\Packages\Common\Application\CommandHandling\Event\EventStream;

interface Resource
{
    public static function createFromArray(array $row);
    public function getLastPersisted();
    public function toArray(): array;
    public function getRecordedEvents(): EventStream;
}