<?php

namespace JiraTempoApi\Repositories\Base;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\HttpClient\Client;

abstract class TempoRepository extends Repository
{
    /** @var JiraApiClient */
    protected $jiraApiClient;

    /** @var TempoApiClient */
    protected $tempoApiClient;

    public function __construct(JiraApiClient $jiraApiClient, TempoApiClient $tempoApiClient)
    {
        $this->jiraApiClient = $jiraApiClient;
        $this->tempoApiClient = $tempoApiClient;
    }
}