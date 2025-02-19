<?php

namespace Klongchu\DocuWare\Responses\Search;

use Klongchu\DocuWare\DTO\DocumentPaginator;
use Klongchu\DocuWare\Events\DocuWareResponseLog;
use Klongchu\DocuWare\Support\EnsureValidResponse;
use Exception;
use Saloon\Http\Response;

final class GetSearchResponse
{
    public static function fromResponse(Response $response, $page, $perPage): DocumentPaginator
    {
        event(new DocuWareResponseLog($response));

        try {
            EnsureValidResponse::from($response);

            $data = $response->throw()->json();
        } catch (Exception $e) {
            return DocumentPaginator::fromFailed($e);
        }

        return DocumentPaginator::fromJson(
            $data,
            $page,
            $perPage,
        );
    }
}
