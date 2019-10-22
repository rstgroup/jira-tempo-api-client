<?php
declare(strict_types=1);

namespace JiraTempoApi\Clients;

use JiraRestApi\Configuration\ConfigurationInterface;
use JiraRestApi\JiraClient;
use JiraRestApi\JiraException;
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

    /**
     * @param resource|null $curlObject
     * @throws JiraException
     */
    public function __construct(
        ConfigurationInterface $configuration = null,
        LoggerInterface $logger = null,
        string $path = './',
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
    public function exec($context, $post_data = null, $custom_request = null, $cookieFile = null): string
    {
        $path = sprintf('%s/%s', $this->baseUri, $context);
        return parent::exec(PathFormatter::format($path), $post_data, $custom_request, $cookieFile);
    }

    public function getUsers(): Users
    {
        if (isset($this->repositories['users'])) {
            return $this->repositories['users'];
        }

        $this->repositories['users'] = new Users($this);
        return $this->repositories['users'];
    }

    public function getLog(): Logger
    {
        return $this->log;
    }

    public function getJsonMapper(): JsonMapper
    {
        return $this->json_mapper;
    }
}