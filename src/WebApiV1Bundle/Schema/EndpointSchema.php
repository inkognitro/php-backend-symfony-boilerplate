<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class EndpointSchema
{
    private $requestMethod;
    private $urlFragments;
    private $openApiData;

    private function __construct(
        RequestMethod $requestMethod,
        UrlFragments $urlFragments,
        array $openApiData
    )
    {
        $this->requestMethod = $requestMethod;
        $this->urlFragments = $urlFragments;
        $this->openApiData = $openApiData;
    }

    public static function create(RequestMethod $requestMethod, UrlFragments $urlFragments): self
    {
        return new self($requestMethod, $urlFragments, []);
    }

    /** @param $tags String[] */
    public function setTags(array $tags): self
    {
        return new self($this->requestMethod, $this->urlFragments, array_merge($this->openApiData, [
            'tags' => $tags
        ]));
    }

    public function setSummary(string $summary): self
    {
        return new self($this->requestMethod, $this->urlFragments, array_merge($this->openApiData, [
            'summary' => $summary
        ]));
    }

    public function setDescription(string $description): self
    {
        return new self($this->requestMethod, $this->urlFragments, array_merge($this->openApiData, [
            'description' => $description
        ]));
    }

    public function getPath(): string
    {
        return '/' . implode('/', $this->urlFragments->toArray());
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
        return $this->requestMethod->toString();
    }

    public function toOpenApiPathMethodFragment(): array
    {
        $data = [];
        $keysToAdd = [
            'tags',
            'summary',
            'description',
        ];
        foreach($keysToAdd as $keyToAdd) {
            if(isset($this->openApiData[$keyToAdd])) {
                $data[$keyToAdd] = $this->openApiData[$this->openApiData[$keyToAdd]];
            }
        }
        return $data;
    }
}