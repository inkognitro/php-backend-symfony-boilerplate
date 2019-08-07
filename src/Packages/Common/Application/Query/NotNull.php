<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

final class NotNull implements Condition
{
    private $attribute;

    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }
}