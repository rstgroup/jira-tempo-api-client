<?php

namespace JiraTempoApi\Repositories\Base;

use JiraTempoApi\Clients\JiraApiClient;
use KHerGe\JSON\JSON;

class JiraApiRepository extends Repository
{
    /** @var JSON */
    protected $json;

    /** @var JiraApiClient */
    protected $jiraApiClient;

    public function __construct(JiraApiClient $jiraApiClient)
    {
        $this->jiraApiClient = $jiraApiClient;
        $this->json = new JSON();
    }
}