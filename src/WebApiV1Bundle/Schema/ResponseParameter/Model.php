<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\ResponseParameter;

final class Model
{
    private $name;
    private $referenceId;
    private $parameter;

    public function __construct(string $name, string $referenceId, ResponseParameter $parameter)
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

    public function toResponseParameter(): ResponseParameter
    {
        return $this->parameter;
    }
}