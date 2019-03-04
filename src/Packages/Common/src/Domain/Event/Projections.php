<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

final class Projections
{
    private $projections;

    /** @param $projections Projection[] */
    public function __construct(array $projections)
    {
        $this->projections = $projections;
    }

    /** @return Projection[] */
    public function toArray(): array
    {
        return $this->projections;
    }
}