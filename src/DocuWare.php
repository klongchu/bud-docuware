<?php

namespace Klongchu\DocuWare;

use Klongchu\DocuWare\DTO\Cookie;
use Klongchu\DocuWare\Events\DocuWareResponseLog;
use Klongchu\DocuWare\Requests\Auth\GetLogoffRequest;
use Klongchu\DocuWare\Requests\Auth\PostLoginRequest;
use Klongchu\DocuWare\Support\Auth;
use Klongchu\DocuWare\Support\EnsureValidCookie;
use Klongchu\DocuWare\Support\EnsureValidCredentials;
use Klongchu\DocuWare\Support\EnsureValidResponse;
use GuzzleHttp\Cookie\CookieJar;
use Saloon\Exceptions\InvalidResponseClassException;
use Saloon\Exceptions\PendingRequestException;

class DocuWare
{
    /**
     * @throws InvalidResponseClassException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws PendingRequestException
     */
    public function cookie(string $url, string $username, string $password, $rememberMe = false, $redirectToMyselfInCaseOfError = false, $licenseType = null): Cookie
    {
        $cookieJar = new CookieJar();

        $request = new PostLoginRequest(
            $url,
            $username,
            $password,
            $rememberMe,
            $redirectToMyselfInCaseOfError,
            $licenseType
        );

        $request->config()->add('cookies', $cookieJar);

        $response = $request->send();

        event(new DocuWareResponseLog($response));

        EnsureValidResponse::from($response);

        return Cookie::make($cookieJar);

    }

    /**
     * @throws InvalidResponseClassException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws PendingRequestException
     */
    public function login(): void
    {
        EnsureValidCredentials::check();
        // Checks if already logged in, if not, logs in
        EnsureValidCookie::check();
    }

    /**
     * @throws InvalidResponseClassException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws PendingRequestException
     */
    public function logout(): void
    {
        // SoloRequest
        $request = new GetLogoffRequest();

        $response = $request->send();

        event(new DocuWareResponseLog($response));

        Auth::forget();

        $response->throw();
    }

    public function searchRequestBuilder(): DocuWareSearchRequestBuilder
    {
        return new DocuWareSearchRequestBuilder();
    }

    public function url(): DocuWareUrl
    {
        return new DocuWareUrl();
    }
}
