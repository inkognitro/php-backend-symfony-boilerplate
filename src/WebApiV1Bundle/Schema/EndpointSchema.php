<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

use App\WebApiV1Bundle\Schema\Parameter\ObjectParameterSchema;

final class EndpointSchema
{
    private $requestMethod;
    private $urlFragments;
    private $openApiData;
    private $responseSchemas;
    private $authKeyNeeded;
    private $showInDocumentation;
    private $requestBodyParams;

    private function __construct(
        RequestMethod $requestMethod,
        UrlFragments $urlFragments,
        array $openApiData,
        ResponseSchemas $responseSchemas,
        bool $authKeyNeeded,
        bool $showInDocumentation,
        ObjectParameterSchema $requestBodyParams
    ) {
        $this->requestMethod = $requestMethod;
        $this->urlFragments = $urlFragments;
        $this->openApiData = $openApiData;
        $this->responseSchemas = $responseSchemas;
        $this->authKeyNeeded = $authKeyNeeded;
        $this->showInDocumentation = $showInDocumentation;
        $this->requestBodyParams = $requestBodyParams;
    }

    public static function create(RequestMethod $requestMethod, UrlFragments $urlFragments): self
    {
        return new self($requestMethod, $urlFragments, [], new ResponseSchemas([]), false, true, ObjectParameterSchema::create());
    }

    public function getRequestBodyParameterSchema(): ObjectParameterSchema
    {
        return $this->requestBodyParams;
    }

    private function set(array $data): self
    {
        return new self(
            ($data['requestMethod'] ?? $this->requestMethod),
            ($data['urlFragments'] ?? $this->urlFragments),
            ($data['openApiData'] ?? $this->openApiData),
            ($data['responseSchemas'] ?? $this->responseSchemas),
            ($data['authKeyNeeded'] ?? $this->authKeyNeeded),
            ($data['showInDocumentation'] ?? $this->showInDocumentation),
            ($data['requestBodyParams'] ?? $this->requestBodyParams)
        );
    }

    /** @param $tags String[] */
    public function setTags(array $tags): self
    {
        return self::set([
            'openApiData' => array_merge($this->openApiData, [
                'tags' => $tags
            ]),
        ]);
    }

    public function setSummary(string $summary): self
    {
        return self::set([
            'openApiData' => array_merge($this->openApiData, [
                'summary' => $summary
            ]),
        ]);
    }

    public function setDescription(string $description): self
    {
        return self::set([
            'openApiData' => array_merge($this->openApiData, [
                'description' => $description
            ])
        ]);
    }

    public function setAuthKeyNeeded(bool $authKeyNeeded): self
    {
        return self::set([
            'authKeyNeeded' => $authKeyNeeded,
        ]);
    }

    public function addResponseSchema(ResponseSchema $responseSchema): self
    {
        return self::set([
            'responseSchemas' => $this->responseSchemas->add($responseSchema),
        ]);
    }

    public function setShowInDocumentation(bool $showInDocumentation): self
    {
        return self::set([
            'showInDocumentation' => $showInDocumentation,
        ]);
    }

    public function setRequestBodyParams(ObjectParameterSchema $requestBodyParams): self
    {
        return self::set([
            'requestBodyParams' => $requestBodyParams,
        ]);
    }

    public function mustBeShownInDocumentation(): bool
    {
        return $this->showInDocumentation;
    }

    public function getPath(): string
    {
        return $this->urlFragments->toPath();
    }

    public function getRequestMethod(): RequestMethod
    {
        return $this->requestMethod;
    }

    public function getOpenApiPath(): string
    {
        return $this->getPath();
    }

    public function getOpenApiMethod(): string
    {
        return strtolower($this->requestMethod->toString());
    }

    private function createOpenApiV2RequestBodyParam(): array
    {
        return [
            'in' => 'body',
            'name' => 'body',
            'required' => 'true',
            'schema' => $this->requestBodyParams->toRequestParameterOpenApiV2Array()
        ];
    }

    public function toOpenApiV2Array(): array
    {
        $data = array_merge($this->openApiData, [
            'produces' => $this->getResponseContentTypes(),
            'responses' => $this->getResponses(),
            'parameters' => [$this->createOpenApiV2RequestBodyParam()],
        ]);
        if ($this->authKeyNeeded) {
            $data['security'] = ['ApiKeyAuthentication'];
        }
        return $data;
    }

    private function getResponses(): array
    {
        $responses = [];
        foreach ($this->responseSchemas->toArray() as $schema) {
            $responses[$schema->getHttpStatusCode()] = $schema->toOpenApiV2Array();
        }
        return $responses;
    }

    private function getResponseContentTypes(): array
    {
        $contentTypes = [];
        foreach ($this->responseSchemas->toArray() as $schema) {
            $contentTypes[] = $schema->getContentType();
        }
        return $contentTypes;
    }
}