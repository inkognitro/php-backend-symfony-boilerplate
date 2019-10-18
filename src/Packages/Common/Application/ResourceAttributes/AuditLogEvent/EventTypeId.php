<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\Attribute;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;

final class EventTypeId implements Attribute
{
    private $id;
    private const EVENT_TO_TYPE_ID_MAPPING = [
        UserWasCreated::class => 'UserManagement/UserWasCreated/V1',
    ];

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(string $eventClassName): self
    {
        return new self(self::EVENT_TO_TYPE_ID_MAPPING[$eventClassName]);
    }

    public function toString(): string
    {
        return $this->id;
    }
}