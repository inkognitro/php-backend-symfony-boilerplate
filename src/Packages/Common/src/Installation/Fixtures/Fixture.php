<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Fixtures;

interface Fixture
{
    public function execute(): void;
}