<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\Event\Event;
use App\Packages\Common\Application\CommandHandling\Event\EventStream;
use App\Packages\Common\Application\CommandHandling\Event\Payload;
use App\Resources\Application\AbstractResource;
use App\Resources\Application\Application\User\Event\UserWasChanged;
use App\Resources\Application\Application\User\Event\UserWasCreated;
use App\Resources\Application\User\Property\EmailAddress;
use App\Resources\Application\User\Property\UserId;
use App\Resources\Application\User\Property\Username;
use Ramsey\Uuid\Uuid;

final class User extends AbstractResource
{
    private $id;
    private $username;
    private $emailAddress;

    protected function __construct(
        EventStream $recordedEvents,
        ?array $persistedData,
        string $id,
        string $username,
        string $emailAddress
    )
    {
        parent::__construct($recordedEvents, $persistedData);
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public static function create(array $userData, AuthUser $authUser): self
    {
        $persistedData = null;
        return new self(
            new EventStream([
                UserWasCreated::occur(Payload::fromData($userData), $authUser)
            ]),
            $persistedData,
            (isset($userData['id']) ? $userData['id'] : Uuid::uuid4()->toString()),
            ($array['username'] ?? ''),
            ($array['emailAddress'] ?? '')
        );
    }

    public function change(array $data, AuthUser $authUser): void
    {
        if (count($data) === 0) {
            return;
        }

        $userData = array_merge($this->toArray(), $data);
        if (!$this->isEqual(self::createFromArray($userData))) {
            return;
        }

        $previousPayload = Payload::fromData($this->toArray());

        if (isset($data['emailAddress'])) {
            $this->emailAddress = $data['emailAddress'];
        }

        if (isset($data['username'])) {
            $this->username = $data['username'];
        }

        $payload = Payload::fromData($this->toArray());

        $this->recordedEvents[] = UserWasChanged::occur($payload, $previousPayload, $authUser);
    }

    private function isEqual(self $user): bool
    {
        if (!$this->getId()->equals($user->getId())) {
            return false;
        }
        if (!$this->getUsername()->equals($user->getUsername())) {
            return false;
        }
        if (!$this->getEmailAddress()->equals($user->getEmailAddress())) {
            return false;
        }
        return true;
    }

    public function getLastPersisted(): ?self
    {
        if ($this->persistedData === null) {
            return null;
        }
        return self::createFromArray($this->persistedData);
    }

    public static function createFromArray(array $userData): self
    {
        return new self(
            new EventStream([]),
            $userData,
            $userData['id'],
            $userData['username'],
            $userData['emailAddress']
        );
    }

    public function getId(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function getUsername(): Username
    {
        return Username::fromString($this->username);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->emailAddress);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'emailAddress' => $this->emailAddress
        ];
    }
}