<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain;

use App\Packages\JobQueueManagement\Application\Command\CreateJob;
use App\Packages\JobQueueManagement\Domain\JobValidation\JobValidator;
use App\Packages\Common\Utilities\HandlerResponse\Response;
use App\Packages\Common\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Packages\Common\Application\Query\QueueJob\Attributes\Attributes\Command;
use App\Packages\Common\Application\Query\QueueJob\Attributes\QueueJobId;

final class CreateJobHandler
{
    private $jobRepository;
    private $validator;

    public function __construct(JobRepository $jobRepository, JobValidator $validator)
    {
        $this->jobRepository = $jobRepository;
        $this->validator = $validator;
    }

    public function handle(CreateJob $command): Response
    {
        $this->validator->validateCreation($command);
        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }
        $jobAggregate = JobAggregate::create(
            QueueJobId::fromString($command->getJobId()),
            Command::fromCommand($command->getQueueCommand()),
            $command->getExecutor()
        );
        $this->jobRepository->save($jobAggregate);
        return new ResourceCreatedResponse($this->validator->getWarnings());
    }
}