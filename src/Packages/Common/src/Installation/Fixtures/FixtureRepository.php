<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Fixtures;

use App\IterableConverter;

final class FixtureRepository
{
    private $fixtures;

    public function __construct(iterable $fixtures)
    {
        $this->fixtures = IterableConverter::convertToArray($fixtures);
        usort($this->fixtures, [$this, 'compareFixtures']);
    }

    private function compareFixtures(AbstractFixture $a, AbstractFixture $b): int
    {
        if($a->getSequenceNumber() < $b->getSequenceNumber()) {
            return -1;
        }

        if($a->getSequenceNumber() > $b->getSequenceNumber()) {
            return 1;
        }

        return 0;
    }

    public function findAll(): Fixtures
    {
        return new Fixtures($this->fixtures);
    }
}