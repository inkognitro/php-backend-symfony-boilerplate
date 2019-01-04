<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\Packages\Common\Installation\Fixtures\Fixture;

final class Fixtures
{
    private $fixtures;

    /** @param $fixtures Fixture[] */
    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /** @return Fixture[] */
    public function toCollection(): array
    {
        return $this->fixtures;
    }
}