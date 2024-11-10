<?php

namespace Klongchu\DocuWare\Responses\Dialogs;

use Klongchu\DocuWare\DTO\Dialog;
use Klongchu\DocuWare\Events\DocuWareResponseLog;
use Klongchu\DocuWare\Support\EnsureValidResponse;
use Saloon\Http\Response;

final class GetDialogResponse
{
    public static function fromResponse(Response $response): Dialog
    {
        event(new DocuWareResponseLog($response));

        EnsureValidResponse::from($response);

        $dialog = $response->throw()->json();

        return Dialog::fromJson($dialog);
    }
}
