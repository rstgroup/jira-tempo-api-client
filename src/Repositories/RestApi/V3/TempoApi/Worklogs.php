<?php

namespace JiraTempoApi\Repositories\RestApi\V3\TempoApi;

use Exception;
use JiraRestApi\Issue\IssueService;
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
     * @return Response
     */
    public function getWorklogs($parameters = [])
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
     * @param array $body
     * @return Response
     */
    public function postWorklogs($body = [])
    {
        $request = $this->request
            ->post()
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve an existing worklog for the given id
     * @param string $worklogId
     * @return Exception|Response
     */
    public function getWorklogById($worklogId)
    {
        $request = $this->request
            ->get($worklogId);

        return $this->tempoApiClient->send($request);
    }

    /**
     *
     * @param string $worklogId
     * @return Exception|Response
     */
    public function putWorklogById($worklogId, $body = [])
    {
        $request = $this->request
            ->put($worklogId)
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /**
     *
     * @param string $worklogId
     * @return Exception|Response
     */
    public function deleteWorklogById($worklogId)
    {
        $request = $this->request
            ->delete($worklogId);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all work attribute values for the worklog
     * @param string $worklogId
     * @return Exception|Response
     */
    public function getWorkAttributesValues($worklogId)
    {
        $request = $this->request
            ->get(
                sprintf('%s/work-attribute-values', $worklogId)
            );

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve a specific work attribute value for the worklog
     * @param string $worklogId
     * @param string $key
     * @return Exception|Response
     */
    public function getWorkAttributesValuesByKey($worklogId, $key)
    {
        $request = $this->request
            ->get(
                sprintf('%s/work-attribute-values/%s', $worklogId, $key)
            );

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve an existing worklog for the given JIRA worklog id
     * @param string $jiraWorklogId
     * @return Exception|Response
     */
    public function getWorklogByJiraWorklogId($jiraWorklogId)
    {
        $request = $this->request
            ->get(
                sprintf('jira/%s', $jiraWorklogId)
            );

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve worklogs associated to the given JIRA filter id
     * @param string $jirafilterId
     * @return Exception|Response
     */
    public function getWorklogByJiraFilterId($jiraFilterId, $parameters = [])
    {
        $request = $this->request
            ->get(
                sprintf('jira/filter/%s', $jiraFilterId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all worklogs associated to the given account key
     * @param string $accountKey
     * @return Exception|Response
     */
    public function getAllWorklogsByAccountKey($accountKey, $parameters = [])
    {
        $request = $this->request
            ->get(
                sprintf('account/%s', $accountKey)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all worklogs associated to the given account key
     * @param string $projectKey
     * @return Exception|Response
     */
    public function getAllWorklogsByProjectKey($projectKey, $parameters = [])
    {
        $request = $this->request
            ->get(
                sprintf('project/%s', $projectKey)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all worklogs associated to the given team id
     * @param string $teamId
     * @return Exception|Response
     */
    public function getAllWorklogsByTeamId($teamId, $parameters = [])
    {
        $request = $this->request
            ->get(
                sprintf('team/%s', $teamId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all worklogs associated to the given user
     * @param string $accountId
     * @return Exception|Response
     */
    public function getAllWorklogsByUserAccountId($accountId, $parameters = [])
    {
        $request = $this->request
            ->get(
                sprintf('user/%s', $accountId)
            )
            ->withParameters($parameters);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all worklogs associated to the given user
     * @param string $issueKey
     * @return Exception|Response
     */
    public function getAllWorklogsByIssueKey($issueKey, $parameters = [])
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
     * @param string $username
     * @param array $parameters
     * @return
     * @throws JiraException
     * @throws DecodeException
     * @throws UnknownException
     */
    public function getAllWorklogsByUser($username, $parameters = [])
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
     * @param UserWorklogs $userWorklogs
     * @return array
     */
    public function getAllIssuesKeysByUser($userWorklogs)
    {
        return array_unique(
            array_map(
                function (Issue $issue) {
                    return $issue->getKey();
                },
                $userWorklogs->getIssues()
            )
        );
    }

    public function getUserIssuesByFilter($jqlToJoinByAnd = [])
    {
        $issueService = new IssueService();
        $jql = implode(' AND ', $jqlToJoinByAnd);
        $result = $issueService->search($jql, 0, 250);

        return $result->getIssues();
    }


    /**
     * @param UserWorklogs $userWorklogs
     * @return array|\JiraRestApi\Issue\Issue[]
     * @throws DecodeException
     * @throws JiraException
     * @throws UnknownException
     */
    public function getIssuesByKeysFromUserIssues($userWorklogs)
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