<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Application\Resources\Events\EventStream;
use App\Packages\Common\Domain\Event\ProjectionRepository;

final class EventDispatcher
{
    private $repository;

    public function __construct(ProjectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function dispatch(EventStream $events): void
    {
        foreach($events->toCollection() as $event) {
            $this->projectEvent($event);
            //todo: execute event subscribers here!
        }
    }

    public function projectEvent(AbstractEvent $event): void
    {
        $projections = $this->repository->findAll();
        foreach($projections->toCollection() as $projection) {
            $projection->project($event);
        }
    }
}