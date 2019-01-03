<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\Packages\UserManagement\Installation\Migrations\UserFixture;

final class FixtureRepository
{
    private const ORDERED_FIXTURES = [
        UserFixture::class,
    ];

    public function findAll(): Fixtures
    {
        $fixtures = [];
        foreach($fixtures as $fixtureClassName) {
            if(!in_array($fixtureClassName, self::ORDERED_FIXTURES)) {
                continue;
            }
            $fixtures[] = new $fixtureClassName();
        }
        return new Fixtures($fixtures);
    }
}