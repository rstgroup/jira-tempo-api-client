<?php
declare(strict_types=1);

namespace JiraTempoApi\Clients;

use JiraTempoApi\HttpClient\Client;

class TempoApiClient extends Client
{
    public static function create(): TempoApiClient
    {
        return new self(
            'https://api.tempo.io/',
            'core/3/',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', getenv('TEMPO_TOKEN'))
            ]
        );
    }
}