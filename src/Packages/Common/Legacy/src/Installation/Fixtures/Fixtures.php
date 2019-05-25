<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Fixtures;

final class Fixtures
{
    private $fixtures;

    /** @param $fixtures AbstractFixture[] */
    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /** @return AbstractFixture[] */
    public function toArray(): array
    {
        return $this->fixtures;
    }
}