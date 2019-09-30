<?php

namespace JiraTempoApi\Repositories\RestApi\V3\JiraApi;

use JiraRestApi\JiraClient;
use JiraRestApi\JiraException;
use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Domain\Model\UserAccountIds;
use JiraTempoApi\Repositories\Base\Repository;
use KHerGe\JSON\Exception\DecodeException;
use KHerGe\JSON\Exception\UnknownException;
use KHerGe\JSON\JSON;

class Users extends Repository
{
    protected $basePath = '/user';

    protected $json;

    /** @var JiraApiClient */
    private $jiraApiClient;

    public function __construct(JiraApiClient $jiraApiClient)
    {
        $this->jiraApiClient = $jiraApiClient;
        $this->json = new JSON();
    }

    /**
     * Get account IDs for users
     * @param array $userNames
     * @return array
     * @throws DecodeException
     * @throws JiraException
     * @throws UnknownException
     */
    public function getAccountIdsByUserNames($userNames = [])
    {
        $response = $this->jiraApiClient->exec(
            sprintf(
                '%s/%s%s',
                $this->basePath,
                'bulk/migration',
                $this->toHttpQueryParameter(['username' => $userNames])
            )
        );
        $this->jiraApiClient->getLog()->info("Result=\n".$response);

        return $this->json->decode($response);
    }

    /**
     * convert to query array to http query parameter.
     *
     * @param $paramArray
     *
     * @return string
     */
    public function toHttpQueryParameter($paramArray)
    {
        $queryParam = '?';

        $first = true;
        foreach ($paramArray as $key => $value) {
            $v = null;

            if (is_array($value)) {
                $v = implode(sprintf('&%s=', $key), $value);
            } else {
                $v = $value;
            }

            $prefix = !$first ? '&': '';
            $queryParam .= sprintf('%s%s=%s', $prefix, $key,$v);
            $first = false;
        }

        return $queryParam;
    }
}