<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Domain\Event\EventStream;
use App\Packages\Common\Application\Domain\Event\Payload;
use App\Resources\Common\Application\Domain\Aggregate;
use App\Resources\User\Application\Domain\Event\UserWasChanged;
use App\Resources\User\Application\Domain\Event\UserWasCreated;
use App\Resources\User\Application\User as QueryUser;
use Ramsey\Uuid\Uuid;

final class User extends Aggregate
{
    private $persistedUser;
    private $currentUser;

    protected function __construct(EventStream $recordedEvents, ?QueryUser $persistedUser, QueryUser $currentUser)
    {
        parent::__construct($recordedEvents);
        $this->persistedUser = $persistedUser;
        $this->currentUser = $currentUser;
    }

    public static function fromUser(QueryUser $user): self
    {
        return new self(new EventStream([]), $user, $user);
    }

    public function toQueryUser(): QueryUser
    {
        return $this->currentUser;
    }

    public static function create(array $userData, AuthUser $authUser): self
    {
        $persistedUser = null;
        $currentUser = QueryUser::fromArray([
            'id' => (isset($userData['id']) ? $userData['id'] : Uuid::uuid4()->toString()),
            'username' => $userData['username'],
            'emailAddress' => $userData['emailAddress']
        ]);
        return new self(
            new EventStream([
                UserWasCreated::occur(Payload::fromData($userData), $authUser)
            ]),
            $persistedUser,
            $currentUser
        );
    }

    public function change(array $userData, AuthUser $authUser): void
    {
        if (count($userData) === 0) {
            return;
        }

        $changedUserData = array_merge($this->getUserDataByUser($this->currentUser), $userData);
        $changedUser = $this->getUserByUserData($changedUserData);

        if ($this->currentUser->isEqualTo($changedUser)) {
            return;
        }

        $this->currentUser = $changedUser;

        $previousPayload = Payload::fromData($this->currentUser->toArray());
        $payload = Payload::fromData($changedUser->toArray());
        $this->recordedEvents[] = UserWasChanged::occur($payload, $previousPayload, $authUser);
    }

    private function getUserByUserData(array $userData): QueryUser
    {
        return QueryUser::fromArray([
            'id' => $userData['id'],
            'username' => $userData['username'],
            'emailAddress' => $userData['emailAddress']
        ]);
    }

    private function getUserDataByUser(QueryUser $user): array
    {
        return [
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername()->toString(),
            'emailAddress' => $user->getEmailAddress()->toString()
        ];
    }
}