<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

interface StateManager
{
    public function beginTransaction(string $savePointName): void;
    public function rollbackTransaction(string $savePointName): void;
    public function commitTransaction(): void;
}