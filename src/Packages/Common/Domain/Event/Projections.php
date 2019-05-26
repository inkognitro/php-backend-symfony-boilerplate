<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

final class Projections
{
    private $projections;

    private function __construct(array $projections)
    {
        $this->projections = $projections;
    }

    /** @param $projections Projection[] */
    public static function fromArray(array $projections): self
    {
        return new self($projections);
    }

    /** @return Projection[] */
    public function toArray(): array
    {
        return $this->projections;
    }
}