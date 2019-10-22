<?php
declare(strict_types=1);

namespace JiraTempoApi\Repositories\RestApi\V3\JiraApi;

use JiraRestApi\JiraException;
use JiraTempoApi\HttpClient\Formatter\QueryParametersFormatter;
use JiraTempoApi\Repositories\Base\JiraApiRepository;
use KHerGe\JSON\Exception\DecodeException;
use KHerGe\JSON\Exception\UnknownException;

class Users extends JiraApiRepository
{
    protected $basePath = '/user';

    /**
     * Get account IDs for users
     * @return array
     * @throws DecodeException
     * @throws JiraException
     * @throws UnknownException
     */
    public function getAccountIdsByUserNames(array $userNames = []): array
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