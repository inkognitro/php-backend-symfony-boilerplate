<?php declare(strict_types=1);

namespace App\Packages\Resources\Property;

use App\Packages\Resources\Validation\Rules;

final class Property
{
    const UUID = 'uuid';
    const TEXT = 'text';
    const MULTI_LINE_TEXT = 'multiLineText';
    const EMAIL = 'email';
    const BOOLEAN = 'boolean';

    private $name;
    private $type;
    private $validationRules;
    private $defaultValue;

    /** @param $defaultValue mixed */
    public function __construct(string $name, string $type, Rules $validationRules, $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->validationRules = $validationRules;
        $this->defaultValue = $defaultValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValidationRules(): Rules
    {
        return $this->validationRules;
    }

    /** @return mixed */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}