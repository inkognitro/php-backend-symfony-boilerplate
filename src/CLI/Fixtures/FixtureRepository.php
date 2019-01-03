<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\IterableConverter;

final class FixtureRepository
{
    private $fixtures;

    public function __construct(iterable $fixtures)
    {
        $this->fixtures = IterableConverter::convertToArray($fixtures);
    }

    public function findAll(): Fixtures
    {
        return new Fixtures($this->fixtures);
    }
}