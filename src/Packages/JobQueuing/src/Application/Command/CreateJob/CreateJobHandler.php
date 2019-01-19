<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Command\CreateJob;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\JobQueuing\Application\Resources\Job\JobId;
use App\Packages\JobQueuing\Application\Resources\Job\JobRepository;
use App\Packages\JobQueuing\Domain\Job\JobAggregate;
use App\Packages\JobQueuing\Domain\Job\JobValidator;

final class CreateJobHandler
{
    private $validator;
    private $jobRepository;
    private $jobFactory;
    private $eventDispatcher;

    public function __construct(
        JobValidator $validator,
        JobRepository $jobRepository,
        JobFactory $jobFactory,
        EventDispatcher $eventDispatcher
    ) {
        $this->validator = $validator;
        $this->jobRepository = $jobRepository;
        $this->jobFactory = $jobFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateJob $command, AuthUser $creator): Response
    {
        $job = $this->jobRepository->findById(JobId::fromString($command->getJobId()));
        if ($job !== null) {
            return new UnauthorizedResponse();
        }

        $job = $this->jobFactory->create($command);

        $this->validator->validate($job);
        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $jobAggregate = JobAggregate::fromNewJob($job, $creator);
        $this->eventDispatcher->dispatch($jobAggregate->getRecordedEvents());
        return new ResourceCreatedResponse($job, $this->validator->getWarnings());
    }
}