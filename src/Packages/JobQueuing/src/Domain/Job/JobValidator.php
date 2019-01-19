<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\Validation\Rules\EmptyOrUuidRule;
use App\Packages\Common\Application\Validation\Rules\NotEmptyRule;
use App\Packages\Common\Domain\AbstractResourceValidator;
use App\Packages\JobQueuing\Application\Resources\Job\Job;
use App\Packages\JobQueuing\Application\Resources\Job\JobRepository;
use InvalidArgumentException;

final class JobValidator extends AbstractResourceValidator
{
    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        parent::__construct();
        $this->jobRepository = $jobRepository;
    }

    public function validate(AbstractResource $job): void
    {
        if(!$job instanceof Job) {
            throw new InvalidArgumentException('Variable $resource must be an instance of ' . Job::class . '!');
        }
        $this->warnings = new MessageBag();
        $this->errors = new MessageBag();
        $this->validateJobId($job);
    }

    private function validateJobId(Job $job): void
    {
        $errorKey = 'id';

        $errorMessage = NotEmptyRule::getMessageFromValidation($job->getId()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
            return;
        }

        $errorMessage = EmptyOrUuidRule::getMessageFromValidation($job->getId()->toString());
        if($errorMessage !== null) {
            $this->errors->addMessage($errorKey, $errorMessage);
        }
    }
}