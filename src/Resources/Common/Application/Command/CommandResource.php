<?php declare(strict_types=1);

namespace App\Resources\Common\Application\Command;

use App\Packages\Common\Application\CommandHandling\Event\EventStream;
use App\Packages\Common\Application\Authorization\User as AuthUser;

interface CommandResource
{
    public static function create(array $resourceData, AuthUser $authUser);

    public function getRecordedEvents(): EventStream;
}