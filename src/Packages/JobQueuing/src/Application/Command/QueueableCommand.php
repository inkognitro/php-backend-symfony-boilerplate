<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Command;

use App\Packages\Common\Application\Command\Command;
use JsonSerializable;

interface QueueableCommand extends Command, JsonSerializable
{

}