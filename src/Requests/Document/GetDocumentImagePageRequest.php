<?php

namespace Klongchu\DocuWare\Requests\Document;

use Klongchu\DocuWare\Responses\Document\GetDocumentPreviewResponse;
use Illuminate\Support\Facades\Cache;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDocumentImagePageRequest extends Request implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $fileCabinetId,
        protected readonly string $documentId,
        protected readonly int $page,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/FileCabinets/' . $this->fileCabinetId . '/Rendering/' . $this->documentId . '/Image?page=' . $this->page;
    }

    public function resolveCacheDriver(): LaravelCacheDriver
    {
        return new LaravelCacheDriver(Cache::store(config('docuware.configurations.cache.driver')));
    }

    public function cacheExpiryInSeconds(): int
    {
        return config('docuware.configurations.cache.lifetime_in_seconds', 3600);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return GetDocumentPreviewResponse::fromResponse($response);
    }
}
