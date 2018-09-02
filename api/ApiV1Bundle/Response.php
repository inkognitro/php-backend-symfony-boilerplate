<?php declare(strict_types=1);

interface Response
{
    public function toJson(): string;
}