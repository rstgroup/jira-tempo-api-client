<?php
declare(strict_types=1);

namespace JiraTempoApi\Repositories\RestApi\V3\TempoApi;

use Exception;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueV3;
use JiraRestApi\JiraException;
use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\Domain\Model\Issue;
use JiraTempoApi\Domain\Model\UserAccountIds;
use JiraTempoApi\Domain\Model\UserWorklogs;
use JiraTempoApi\HttpClient\Factory\RequestFactory;
use JiraTempoApi\HttpClient\Request;
use JiraTempoApi\HttpClient\Response;
use JiraTempoApi\Repositories\Base\TempoRepository;
use JsonMapper_Exception;
use KHerGe\JSON\Exception\DecodeException;
use KHerGe\JSON\Exception\UnknownException;

/** @see https://tempo-io.github.io/tempo-api-docs/#worklogs */
class Worklogs extends TempoRepository
{
    /** @var string */
    protected $basePath = '/worklogs';

    /** @var Request */
    private $request;

    public function __construct(JiraApiClient $jiraApiClient, TempoApiClient $tempoApiClient)
    {
        parent::__construct($jiraApiClient, $tempoApiClient);
        $this->request = RequestFactory::startsWith($this->basePath);
    }

    /**
     * Retrieve worklogs
     * @param array|string[] $parameters
     */
    public function getWorklogs(array $parameters = []): Response
    {
        $request = $this->request
            ->get()
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Creates a new worklog
     * @example {
     *      "issueKey": "DUM-1",
     *      "timeSpentSeconds": 3600,
     *      "billableSeconds": 5200,
     *      "startDate": "2017-02-06",
     *      "startTime": "20:06:00",
     *      "description": "Investigating a problem with our external database system", // optional depending on setting in Tempo Admin
     *      "authorAccountId": "1111aaaa2222bbbb3333cccc",
     *      "remainingEstimateSeconds": 7200, // optional depending on setting in Tempo Admin
     *      "attributes": [
     *          {
     *              "key": "_EXTERNALREF_",
     *              "value": "EXT-32548"
     *          },
     *          {
     *              "key": "_COLOR_",
     *              "value": "green"
     *          }
     *      ]
     *  }
     *
     */
    public function postWorklogs(array $body = []): Response
    {
        $request = $this->request
            ->post()
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve an existing worklog for the given id */
    public function getWorklogById(string $worklogId): Response
    {
        $request = $this->request
            ->get($worklogId);

        return $this->tempoApiClient->send($request);
    }

    /** */
    public function putWorklogById(string $worklogId, array $body = []): Response
    {
        $request = $this->request
            ->put($worklogId)
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /** */
    public function deleteWorklogById(string $worklogId): Response
    {
        $request = $this->request
            ->delete($worklogId);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all work attribute values for the worklog */
    public function getWorkAttributesValues(string $worklogId): Response
    {
        $request = $this->request
            ->get(
                sprintf('%s/work-attribute-values', $worklogId)
            );

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve a specific work attribute value for the worklog */
    public function getWorkAttributesValuesByKey(string $worklogId, string $key): Response
    {
        $request = $this->request
            ->get(
                sprintf('%s/work-attribute-values/%s', $worklogId, $key)
            );

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve an existing worklog for the given JIRA worklog id */
    public function getWorklogByJiraWorklogId(string $jiraWorklogId): Response
    {
        $request = $this->request
            ->get(
                sprintf('jira/%s', $jiraWorklogId)
            );

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve worklogs associated to the given JIRA filter id */
    public function getWorklogByJiraFilterId(string $jiraFilterId, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('jira/filter/%s', $jiraFilterId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all worklogs associated to the given account key */
    public function getAllWorklogsByAccountKey(string $accountKey, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('account/%s', $accountKey)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all worklogs associated to the given account key */
    public function getAllWorklogsByProjectKey(string $projectKey, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('project/%s', $projectKey)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all worklogs associated to the given team id */
    public function getAllWorklogsByTeamId(string $teamId, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('team/%s', $teamId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all worklogs associated to the given user */
    public function getAllWorklogsByUserAccountId(string $accountId, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('user/%s', $accountId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all worklogs associated to the given user */
    public function getAllWorklogsByIssueKey(string $issueKey, array $parameters = []): Response
    {
        $request = $this->request
            ->get(
                sprintf('issue/%s', $issueKey)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /** ######## EXTENDED FEATURES ########## */

    /**
     * Retrieve all issue associated to the given user
     * @throws JiraException
     * @throws DecodeException
     * @throws UnknownException
     */
    public function getAllWorklogsByUser(string $username, array $parameters = []): UserWorklogs
    {
        $users = $this->jiraApiClient->getUsers();
        $userAccountIds = $users->getAccountIdsByUserNames([$username]);
        $userAccountId = UserAccountIds::create($userAccountIds)->getFirst();

        $worklogsResponse = $this->getAllWorklogsByUserAccountId($userAccountId->getAccountId(), $parameters);
        /** @var UserWorklogs $userWorklogs */
        $userWorklogs = $worklogsResponse->toObject(UserWorklogs::class);

        return $userWorklogs;
    }

    /**
     * @return string[]
     */
    public function getAllIssuesKeysByUser(UserWorklogs $userWorklogs): array
    {
        return array_unique(
            array_map(
                static function (Issue $issue) {
                    return $issue->getKey();
                },
                $userWorklogs->getIssues()
            )
        );
    }

    /**
     * @return IssueV3[]|Issue[]
     * @throws JiraException
     * @throws JsonMapper_Exception
     */
    public function getUserIssuesByFilter(array $jqlToJoinByAnd = []): array
    {
        $issueService = new IssueService();
        $jql = implode(' AND ', $jqlToJoinByAnd);
        $result = $issueService->search($jql, 0, 250);

        return $result->getIssues();
    }


    /**
     * @param UserWorklogs $userWorklogs
     * @return IssueV3[]|Issue[]
     * @throws JiraException
     * @throws JsonMapper_Exception
     */
    public function getIssuesByKeysFromUserIssues(UserWorklogs $userWorklogs): array
    {
        $issueKeys = array_values(
            $this->getAllIssuesKeysByUser(
                $userWorklogs
            )
        );

        if (count($issueKeys) === 0) {
            return [];
        }

        return $this->getUserIssuesByFilter(
            [sprintf('key in (%s)', implode(',', $issueKeys))]
        );
    }
}