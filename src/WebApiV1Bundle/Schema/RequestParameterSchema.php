<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class RequestParameterSchema
{
    private const STRING_TYPE = 'string';

    private $name;
    private $description;
    private $type;
    private $format;
    private $required;

    private function __construct(string $type, ?string $format, string $name, string $description, bool $required)
    {
        $this->type = $type;
        $this->format = $format;
        $this->name = $name;
        $this->description = $description;
        $this->required = $required;
    }

    public static function createString(string $name): self
    {
        return new self(self::STRING_TYPE, null, $name, '', false);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setRequired(bool $required = true): self
    {
        return new self($this->type, $this->format, $this->name, $this->description, $required);
    }

    public function setDescription(string $description): self
    {
        return new self($this->type, $this->format, $this->name, $description, $this->required);
    }

    public function toOpenApiV2Array(): array
    {
        $data = [
            'type' => $this->type,
            'required' => $this->required,
        ];
        if ($this->format) {
            $data['format'] = $this->format;
        }
        if ($this->description) {
            $data['description'] = $this->description;
        }
        return $data;
    }
}