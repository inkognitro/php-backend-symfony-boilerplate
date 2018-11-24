<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

interface StateManager
{
    public function noticeChanges(): void;
    public function saveNoticedChanges(): void;
    public function discardNoticedChanges(): void;
}