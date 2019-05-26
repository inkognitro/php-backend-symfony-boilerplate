<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Fixtures;

final class Fixtures
{
    private $fixtures;

    /** @param $fixtures Fixture[] */
    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /** @return Fixture[] */
    public function toArray(): array
    {
        return $this->fixtures;
    }
}