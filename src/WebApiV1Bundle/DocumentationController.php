<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\WebApiV1Bundle\Endpoints\SpecificationEndpoint;
use App\WebApiV1Bundle\Response\HtmlResponse;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class DocumentationController
{
    private const DOCUMENTATION_HTML_CACHE_KEY = 'documentationHtml10cc9a5b';
    private $httpResponseFactory;
    private $urlGenerator;
    private $cache;

    public function __construct(HttpResponseFactory $httpResponseFactory, UrlGeneratorInterface $urlGenerator, CacheInterface $cache)
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->cache = $cache;
    }

    public function show(): HttpResponse
    {
        $request = ApiRequest::createFromGlobals();
        $documentationHtml = $this->cache->get(self::DOCUMENTATION_HTML_CACHE_KEY, function (ItemInterface $item) {
            return $this->getDocumentationHtml();
        });
        $response = HtmlResponse::fromHtml($documentationHtml);
        return $this->httpResponseFactory->create($response, $request);
    }

    private function getDocumentationHtml(): string
    {
        $scriptContent = file_get_contents(__DIR__ . '/Resources/Documentation/dist/app.js');
        $specificationUrl = $this->urlGenerator->generate(SpecificationEndpoint::class);
        $scriptContent = str_replace('%specificationUrl%', $specificationUrl, $scriptContent);
        $htmlContent = file_get_contents(__DIR__ . '/Resources/Documentation/dist/index.html');
        $htmlContent = str_replace('%scriptContent%', $scriptContent, $htmlContent);
        return $htmlContent;
    }
}