<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\Packages\Common\Installation\Fixtures\AbstractFixture;

final class Fixtures
{
    private $fixtures;

    /** @param $fixtures AbstractFixture[] */
    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /** @return AbstractFixture[] */
    public function toCollection(): array
    {
        return $this->fixtures;
    }
}