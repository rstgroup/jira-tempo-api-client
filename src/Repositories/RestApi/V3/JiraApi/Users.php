<?php

namespace JiraTempoApi\Repositories\RestApi\V3\JiraApi;

use JiraRestApi\JiraClient;
use JiraRestApi\JiraException;
use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Domain\Model\UserAccountIds;
use JiraTempoApi\HttpClient\Formatter\QueryParametersFormatter;
use JiraTempoApi\Repositories\Base\JiraApiRepository;
use JiraTempoApi\Repositories\Base\Repository;
use KHerGe\JSON\Exception\DecodeException;
use KHerGe\JSON\Exception\UnknownException;
use KHerGe\JSON\JSON;

class Users extends JiraApiRepository
{
    protected $basePath = '/user';

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
                QueryParametersFormatter::toHttpQueryParameter(['username' => $userNames])
            )
        );
        $this->jiraApiClient->getLog()->info("Result=\n".$response);

        return $this->json->decode($response);
    }
}