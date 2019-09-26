<?php

namespace JiraTempoApi\Clients;

use JiraTempoApi\HttpClient\Client;

class TempoApiClient extends Client
{
    /** @return TempoApiClient */
    public static function create()
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