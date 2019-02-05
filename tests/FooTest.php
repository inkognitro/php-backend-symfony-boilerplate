<?php declare(strict_types=1);

namespace App;

use PHPUnit\Framework\TestCase;

final class FooTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertNull(null);
    }
}
