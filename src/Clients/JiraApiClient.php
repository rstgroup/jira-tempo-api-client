<?php

namespace JiraTempoApi\Clients;

use JiraRestApi\JiraClient;
use JiraTempoApi\HttpClient\Formatter\PathFormatter;
use JiraTempoApi\Repositories\Base\Repository;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use JsonMapper;
use Monolog\Logger;

class JiraApiClient extends JiraClient
{
    /** @var string */
    private $baseUri = '';

    /** @var array|Repository[] */
    private $repositories = [];

    /**
     * {@inheritDoc}
     */
    public function exec($context, $post_data = null, $custom_request = null, $cookieFile = null)
    {
        $path = sprintf('%s/%s', $this->baseUri, $context);
        return parent::exec(PathFormatter::format($path), $post_data, $custom_request, $cookieFile);
    }

    /** @return Users */
    public function getUsers()
    {
        if (isset($this->repositories['users'])) {
            return $this->repositories['users'];
        }

        $this->repositories['users'] = new Users($this);
        return $this->repositories['users'];
    }

    /** @return Logger */
    public function getLog()
    {
        return $this->log;
    }

    /** @return JsonMapper */
    public function getJsonMapper()
    {
        return $this->json_mapper;
    }
}