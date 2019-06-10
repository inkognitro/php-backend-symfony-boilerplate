<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\WebApiV1Bundle\Endpoints\SpecificationEndpoint;
use App\WebApiV1Bundle\Response\HtmlResponse;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class DocumentationController
{
    private $httpResponseFactory;
    private $urlGenerator;

    public function __construct(HttpResponseFactory $httpResponseFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function show(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $response = HtmlResponse::fromHtml($this->getDocumentationHtml());
        return $this->httpResponseFactory->create($response, $request);
    }

    private function getDocumentationHtml(): string
    {
        //todo: cache the html output!
        $scriptContent = file_get_contents(__DIR__ . '/Resources/Documentation/dist/app.js');
        $specificationUrl = $this->urlGenerator->generate(SpecificationEndpoint::class);
        $scriptContent = str_replace('%specificationUrl%', $specificationUrl, $scriptContent);
        $htmlContent = file_get_contents(__DIR__ . '/Resources/Documentation/dist/index.html.template');
        $htmlContent = str_replace('%scriptContent%', $scriptContent, $htmlContent);
        return $htmlContent;
    }
}