<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\JobValidation;

use App\Packages\Common\Application\JobQueuing\CreateJob;
use App\Resources\Application\QueueJob\Attributes\QueueJobId;
use App\Utilities\Validation\Messages\DoesAlreadyExistMessage;
use App\Utilities\Validation\Rules\RequiredUuidRule;
use App\Utilities\Validation\Validator;

final class JobValidator extends Validator
{
    private $jobExistsByIdQuery;

    public function __construct(JobExistsByIdQuery $jobExistsByIdQuery)
    {
        parent::__construct();
        $this->jobExistsByIdQuery = $jobExistsByIdQuery;
    }

    public function validateCreation(CreateJob $command): void
    {
        $this->resetMessageBags();
        $this->validateJobId($command->getJobId());
    }

    private function validateJobId(string $jobId): void
    {
        $error = RequiredUuidRule::findError($jobId);
        if ($error !== null) {
            $this->errors = $this->errors->addMessage(QueueJobId::getPayloadKey(), $error);
            return;
        }
        if ($this->jobExistsByIdQuery->execute(QueueJobId::fromString($jobId))) {
            $this->errors = $this->errors->addMessage(QueueJobId::getPayloadKey(), new DoesAlreadyExistMessage());
        }
    }
}