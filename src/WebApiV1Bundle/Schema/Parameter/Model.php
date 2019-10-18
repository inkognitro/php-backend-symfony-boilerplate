<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

final class Model
{
    private $name;
    private $referenceId;
    private $parameter;

    public function __construct(string $name, string $referenceId, ObjectParameterSchema $parameter)
    {
        $this->name = $name;
        $this->referenceId = $referenceId;
        $this->parameter = $parameter;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getObjectParameter(): ParameterSchema
    {
        return $this->parameter;
    }
}