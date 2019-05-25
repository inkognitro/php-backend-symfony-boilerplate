<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Commands\CreateJob;

use App\Utilities\AuthUser as AuthUser;
use App\Utilities\Auth\AuthUserFactory;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\JobQueuing\Domain\Job\Job;
use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;
use App\Packages\JobQueuing\Domain\Job\JobRepository;
use App\Packages\JobQueuing\Domain\Job\JobAggregate;
use App\Packages\JobQueuing\Domain\Job\JobValidator;

final class CreateJobHandler
{
    private $authUserFactory;
    private $validator;
    private $jobRepository;

    public function __construct(AuthUserFactory $authUserFactory, JobValidator $validator, JobRepository $jobRepository)
    {
        $this->authUserFactory = $authUserFactory;
        $this->validator = $validator;
        $this->jobRepository = $jobRepository;
    }

    public function handle(CreateJob $command, AuthUser $creator = null): Response
    {
        if ($creator === null) {
            $creator = $this->authUserFactory->createSystemUser();
        }

        $job = $this->jobRepository->findById(JobId::fromString($command->getJobId()));
        if ($job !== null) {
            return new UnauthorizedResponse();
        }

        $job = $this->createJobFromCommand($command);

        $this->validator->validate($job);
        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $jobAggregate = JobAggregate::fromNewJob($job, $creator);
        $this->jobRepository->save($jobAggregate);
        return new ResourceCreatedResponse($this->validator->getWarnings());
    }

    private function createJobFromCommand(CreateJob $command): Job
    {
        $createdAt = null;
        $executedAt = null;
        return new Job(
            JobId::fromString($command->getJobId()),
            $command->getCommand(),
            $createdAt,
            $executedAt
        );
    }
}