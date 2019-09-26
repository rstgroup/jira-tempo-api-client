<?php

namespace JiraTempoApi\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use JiraTempoApi\HttpClient\Formatter\UriFormatter;

class Client
{
    /** @var GuzzleClient */
    private $guzzleClient;

    /** @var string */
    private $basePath;

    public function __construct($baseUri, $basePath = '', $headers = [])
    {
        $this->basePath = $basePath;
        $this->guzzleClient = new GuzzleClient(array_merge(
            [
                'base_uri' => UriFormatter::format($baseUri),
                'headers' => $headers
            ]
        ));
    }

    public function send(Request $request)
    {
        $headers = [
            'headers' => $request->headers()
        ];

        if ($request->hasBody()) {
            $headers['body'] = $request->body();
        }

        if ($request->hasParameters()) {
            $headers['query'] = $request->parameters();
        }

        try {
            $response = $this->guzzleClient->request(
                $request->method(),
                $request->path($this->basePath),
                $headers
            );

            return Response::fromResponse($response);
        } catch (GuzzleException $exception) {
            return Response::fromException($exception);
        }
    }
}