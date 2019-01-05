<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

use App\IterableConverter;

final class CommandHandlerRepository
{
    /** @var $commandHandlers CommandHandler[] */
    private $commandHandlers;

    private function __construct(iterable $commandHandlers)
    {
        $this->commandHandlers = IterableConverter::convertToArray($commandHandlers);
    }

    public function findByCommand(Command $command): ?CommandHandler
    {
        foreach($this->commandHandlers as $commandHandler) {
            /** @var $commandHandler CommandHandler */
            if(get_class($command) === $commandHandler->getCommandClassName()) {
                return $commandHandler;
            }
        }
        return null;
    }
}