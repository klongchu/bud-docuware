<?php

namespace Klongchu\DocuWare\Responses\Document;

use Klongchu\DocuWare\Events\DocuWareResponseLog;
use Klongchu\DocuWare\Support\EnsureValidResponse;
use Saloon\Http\Response;

final class GetDocumentDownloadResponse
{
    public static function fromResponse(Response $response): string
    {
        event(new DocuWareResponseLog($response));

        EnsureValidResponse::from($response);

        return $response->throw()->body();
    }
}
