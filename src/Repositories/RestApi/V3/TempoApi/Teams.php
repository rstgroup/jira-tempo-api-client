<?php

namespace JiraTempoApi\Repositories\RestApi\V3\TempoApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\HttpClient\Factory\RequestFactory;
use JiraTempoApi\HttpClient\Request;
use JiraTempoApi\HttpClient\Response;
use JiraTempoApi\Repositories\Base\TempoRepository;

/** @see https://tempo-io.github.io/tempo-api-docs/#teams */
class Teams extends TempoRepository
{
    /** @var string */
    protected $basePath = '/teams';

    /** @var Request */
    private $request;

    public function __construct(JiraApiClient $jiraApiClient, TempoApiClient $tempoApiClient)
    {
        parent::__construct($jiraApiClient, $tempoApiClient);
        $this->request = RequestFactory::startsWith($this->basePath);
    }

    /**
     * Retrieve all teams
     * @return Response
     */
    public function getTeams()
    {
        $request = $this->request
            ->get();

        return $this->tempoApiClient->send($request);
    }

    /** 
     * Creates a new team
     * Media type application/json
     * @see https://tempo-io.github.io/tempo-api-docs/#teams
     * @param array $body
     * @return Response
     *
     * @example {
     *   "name": "Team-A",
     *   "summary": "This is the A team",
     *   "leadAccountId": "1111aaaa2222bbbb3333cccc",
     *   "programId": 42
     *  }
     **/
    public function postTeams($body = [])
    {
        $request = $this->request
            ->post()
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve an existing team for the given id
     * @param string $id
     * @return Response
     * @see https://tempo-io.github.io/tempo-api-docs/#teams
     */
    public function getTeam($id)
    {
        $request = $this->request
            ->get($id);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Update an existing team for the given id
     * @param $id
     * @param array $body
     * @return Response
     * @example {
     *   "name": "Team-A",
     *   "summary": "This is the A team",
     *   "leadAccountId": "1111aaaa2222bbbb3333cccc",
     *   "programId": 42
     *  }
     */
    public function putTeam($id, $body = [])
    {
        $request = $this->request
            ->put($id)
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Delete an existing team
     * @param $id
     * @return Response
     */
    public function deleteTeam($id)
    {
        $request = $this->request
            ->delete($id);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all the links for this team
     * @param $id
     * @return Response
     */
    public function getLinks($id)
    {
        $request = $this->request
            ->get(sprintf('%s/links', $id));

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all the members for this team with their active membership
     * @param $id
     * @return Response
     */
    public function getMembers($id)
    {
        $request = $this->request
            ->get(sprintf('%s/members', $id));

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve the member's active membership for this team
     * @param $id
     * @param $accountId
     * @return Response
     */
    public function getMemberByTeamIdAndAccountId($id, $accountId)
    {
        $request = $this->request
            ->get(sprintf('%s/members/%s', $id, $accountId));

        return $this->tempoApiClient->send($request);

    }

    /**
     * Retrieve member's memberships for this team
     * @param $id
     * @param $accountId
     * @return Response
     */
    public function getMembershipByTeamIdAndAccountId($id, $accountId)
    {
        $request = $this->request
            ->get(sprintf('%s/members/%s/memberships', $id, $accountId));

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve all the permissions for this team
     * @param $id
     * @return Response
     */
    public function getPermissions($id)
    {
        $request = $this->request
            ->get(sprintf('%s/permissions', $id));

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve a specific permission belonging to the team
     * @param $id
     * @param $key
     * @return Response
     */
    public function getPermissionsByKey($id, $key)
    {
        $request = $this->request
            ->get(sprintf('%s/permissions/%s', $id, $key));

        return $this->tempoApiClient->send($request);
    }
}