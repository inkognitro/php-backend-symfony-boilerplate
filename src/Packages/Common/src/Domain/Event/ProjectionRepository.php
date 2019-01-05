<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

use App\IterableConverter;

final class ProjectionRepository
{
    private $projections;

    public function __construct(iterable $projections)
    {
        $this->projections = IterableConverter::convertToArray($projections);
    }

    public function findAll(): Projections
    {
        return new Projections($this->projections);
    }
}