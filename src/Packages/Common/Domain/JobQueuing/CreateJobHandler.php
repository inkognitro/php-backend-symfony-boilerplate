<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing;

use App\Packages\Common\Application\JobQueuing\CreateJob;
use App\Packages\Common\Domain\JobQueuing\JobValidation\JobValidator;
use App\Utilities\HandlerResponse\Response;
use App\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Resources\QueueJob\Command;
use App\Resources\QueueJob\QueueJobId;

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