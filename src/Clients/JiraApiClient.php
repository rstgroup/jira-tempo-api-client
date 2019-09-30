<?php

namespace JiraTempoApi\Clients;

use JiraRestApi\Configuration\ConfigurationInterface;
use JiraRestApi\JiraClient;
use JiraTempoApi\HttpClient\Formatter\PathFormatter;
use JiraTempoApi\Repositories\Base\Repository;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use JsonMapper;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class JiraApiClient extends JiraClient
{
    /** @var string */
    private $baseUri = '';

    /** @var array|Repository[] */
    private $repositories = [];

    public function __construct(
        ConfigurationInterface $configuration = null,
        LoggerInterface $logger = null,
        $path = './',
        $curlObject = null
    ) {
        parent::__construct($configuration, $logger, $path);
        if ($curlObject !== null) {
            $this->curl = $curlObject;
        }
    }

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