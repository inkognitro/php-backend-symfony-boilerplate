<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class EndpointSchema
{
    private $requestMethod;
    private $urlFragments;
    private $openApiData;
    private $responseSchemas;

    private function __construct(
        RequestMethod $requestMethod,
        UrlFragments $urlFragments,
        array $openApiData,
        ResponseSchemas $responseSchemas
    )
    {
        $this->requestMethod = $requestMethod;
        $this->urlFragments = $urlFragments;
        $this->openApiData = $openApiData;
        $this->responseSchemas = $responseSchemas;
    }

    public static function create(RequestMethod $requestMethod, UrlFragments $urlFragments): self
    {
        return new self($requestMethod, $urlFragments, [], new ResponseSchemas([]));
    }

    /** @param $tags String[] */
    public function setTags(array $tags): self
    {
        return new self(
            $this->requestMethod,
            $this->urlFragments,
            array_merge($this->openApiData, [
                'tags' => $tags
            ]),
            $this->responseSchemas
        );
    }

    public function setSummary(string $summary): self
    {
        return new self(
            $this->requestMethod,
            $this->urlFragments,
            array_merge($this->openApiData, [
                'summary' => $summary
            ]),
            $this->responseSchemas
        );
    }

    public function setDescription(string $description): self
    {
        return new self(
            $this->requestMethod,
            $this->urlFragments,
            array_merge($this->openApiData, [
                'description' => $description
            ]),
            $this->responseSchemas
        );
    }

    public function addResponseSchema(ResponseSchema $responseSchema): self
    {
        return new self(
            $this->requestMethod,
            $this->urlFragments,
            $this->openApiData,
            $this->responseSchemas->add($responseSchema)
        );
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

    public function toOpenApiV2Array(): array
    {
        return array_merge($this->openApiData, [
            'produces' => $this->getResponseContentTypes(),
            'responses' => $this->getResponses(),
        ]);
    }

    private function getResponses(): array
    {
        $responses = [];
        foreach($this->responseSchemas->toArray() as $schema) {
            $responses[$schema->getHttpStatusCode()] = $schema->toOpenApiV2Array();
        }
        return $responses;
    }

    private function getResponseContentTypes(): array
    {
        $contentTypes = [];
        foreach($this->responseSchemas->toArray() as $schema) {
            $contentTypes[] = $schema->getContentType();
        }
        return $contentTypes;
    }
}