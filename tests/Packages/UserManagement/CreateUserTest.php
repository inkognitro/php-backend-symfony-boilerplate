<?php declare(strict_types=1);

namespace App\Tests\Packages\UserManagement;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Utilities\Validation\Messages\DoesAlreadyExistMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAnEmailAddressMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAUuidMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeLongerThanMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeShorterThanMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\OnlyDefinedCharsAreAllowed;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

final class CreateUserTest extends UserTestCase
{
    public function testCanCreateUser(): void
    {
        $command = CreateUser::fromArray([
            CreateUser::USER_PARAMS => UserParams::fromArray($this->createUserParamsArray(self::USER)),
            CreateUser::SEND_INVITATION => false,
            CreateUser::CREATOR => $this->createSystemAuthUser(),
        ]);

        $response = $this->getCommandBus()->handle($command);
        $this->assertSuccessResponse($response);

        $user = $this->findUser(self::USER[UserId::class], [
            UserId::class,
            Username::class,
            EmailAddress::class,
            RoleId::class,
            Password::class,
        ]);

        self::assertNotNull($user);
        self::assertEquals($user->getId()->toString(), self::USER[UserId::class]);
        self::assertEquals($user->getEmailAddress()->toString(), self::USER[EmailAddress::class]);
        self::assertEquals($user->getUsername()->toString(), self::USER[Username::class]);
        self::assertEquals($user->getRoleId()->toString(), self::USER[RoleId::class]);
        self::assertTrue($user->getPassword()->isSame(self::USER[Password::class]));
    }

    /** @dataProvider canNotCreateUserDataProvider */
    public function testCanNotCreateUser(array $userParams, array $expectedFieldErrors): void
    {
        $command = CreateUser::fromArray([
            CreateUser::USER_PARAMS => UserParams::fromArray($this->createUserParamsArray($userParams)),
            CreateUser::SEND_INVITATION => false,
            CreateUser::CREATOR => $this->createSystemAuthUser(),
        ]);
        $response = $this->getCommandBus()->handle($command);
        $this->assertValidationErrorResponse($response, $expectedFieldErrors);
    }

    public function canNotCreateUserDataProvider(): array
    {
        $validUserParams = self::USER;
        return [
            'empty id' => [
                'userParams' => array_merge($validUserParams, [
                    UserId::class => '',
                ]),
                'expectedFieldErrors' => [
                    UserId::class => new MustNotBeEmptyMessage(),
                ],
            ],
            'redundant id' => [
                'userParams' => array_merge($validUserParams, [
                    UserId::class => self::EXISTING_USER[UserId::class],
                ]),
                'expectedFieldErrors' => [
                    UserId::class => new DoesAlreadyExistMessage(),
                ],
            ],
            'id is not uuid' => [
                'userParams' => array_merge($validUserParams, [
                    UserId::class => 'foo',
                ]),
                'expectedFieldErrors' => [
                    UserId::class => new MustBeAUuidMessage(),
                ],
            ],
            'empty username' => [
                'userParams' => array_merge($validUserParams, [
                    Username::class => '',
                ]),
                'expectedFieldErrors' => [
                    Username::class => new MustNotBeEmptyMessage(),
                ],
            ],
            'redundant username' => [
                'userParams' => array_merge($validUserParams, [
                    Username::class => self::EXISTING_USER[Username::class],
                ]),
                'expectedFieldErrors' => [
                    Username::class => new DoesAlreadyExistMessage(),
                ],
            ],
            'username is shorter than 6 chars' => [
                'userParams' => array_merge($validUserParams, [
                    Username::class => 'foo',
                ]),
                'expectedFieldErrors' => [
                    Username::class => new MustNotBeShorterThanMessage(6 ),
                ],
            ],
            'username is longer than 20 chars' => [
                'userParams' => array_merge($validUserParams, [
                    Username::class => 'foooooooooooooooooooo',
                ]),
                'expectedFieldErrors' => [
                    Username::class => new MustNotBeLongerThanMessage(20 ),
                ],
            ],
            'username has umlauts' => [
                'userParams' => array_merge($validUserParams, [
                    Username::class => 'föö888',
                ]),
                'expectedFieldErrors' => [
                    Username::class => new OnlyDefinedCharsAreAllowed('/^[A-Za-z0-9]*$/' ),
                ],
            ],
            'empty email address' => [
                'userParams' => array_merge($validUserParams, [
                    EmailAddress::class => '',
                ]),
                'expectedFieldErrors' => [
                    EmailAddress::class => new MustNotBeEmptyMessage(),
                ],
            ],
            'email address is not a valid email address' => [
                'userParams' => array_merge($validUserParams, [
                    EmailAddress::class => 'foo',
                ]),
                'expectedFieldErrors' => [
                    EmailAddress::class => new MustBeAnEmailAddressMessage(),
                ],
            ],
            'email address has umlauts' => [
                'userParams' => array_merge($validUserParams, [
                    EmailAddress::class => 'f28e722eöö@bar.com',
                ]),
                'expectedFieldErrors' => [
                    EmailAddress::class => new MustBeAnEmailAddressMessage(),
                ],
            ],
            'redundant email address' => [
                'userParams' => array_merge($validUserParams, [
                    EmailAddress::class => self::EXISTING_USER[EmailAddress::class],
                ]),
                'expectedFieldErrors' => [
                    EmailAddress::class => new DoesAlreadyExistMessage(),
                ],
            ],
        ];
    }
}
