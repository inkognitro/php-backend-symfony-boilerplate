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

    public function merge(self $that): self
    {
        return new self(array_merge($this->toArray(), $that->toArray()));
    }

    /** @return Fixture[] */
    public function toArray(): array
    {
        return $this->fixtures;
    }

    /** @return Fixture[] */
    public function toSortedArray(): array
    {
        $sortedFixtures = $this->fixtures;
        usort($sortedFixtures, function (Fixture $a, Fixture $b): int {
            if ($a->getSequenceNumber() < $b->getSequenceNumber()) {
                return -1;
            }
            if ($a->getSequenceNumber() > $b->getSequenceNumber()) {
                return 1;
            }
            return 0;
        });
        return $sortedFixtures;
    }

    public function isEmpty(): bool
    {
        return (count($this->fixtures) === 0);
    }
}