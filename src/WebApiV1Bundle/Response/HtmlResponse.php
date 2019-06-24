<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class HtmlResponse implements Response
{
    private $html;

    private function __construct(string $html)
    {
        $this->html = $html;
    }

    public static function getHttpStatusCode(): int
    {
        return HttpResponse::HTTP_OK;
    }

    public static function fromHtml(string $html): self
    {
        return new self($html);
    }

    public function toHtml(): string
    {
        return $this->html;
    }

    public static function getSchema(): ResponseSchema
    {
        $description = 'Successful html response';
        return new ResponseSchema(self::getHttpStatusCode(), ResponseSchema::HTML_CONTENT_TYPE, $description);
    }
}