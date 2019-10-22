<?php
declare(strict_types=1);

namespace JiraTempoApi\Repositories\RestApi\V3\TempoApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\Domain\Model\MembersCollection;
use JiraTempoApi\Domain\Model\TeamMembers;
use JiraTempoApi\Domain\Model\TeamsCollection;
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

    /** Retrieve all teams */
    public function getTeams(): Response
    {
        $request = $this->request
            ->get();

        return $this->tempoApiClient->send($request);
    }

    /** 
     * Creates a new team
     * Media type application/json
     * @see https://tempo-io.github.io/tempo-api-docs/#teams
     * @example {
     *   "name": "Team-A",
     *   "summary": "This is the A team",
     *   "leadAccountId": "1111aaaa2222bbbb3333cccc",
     *   "programId": 42
     *  }
     **/
    public function postTeams(array $body = []): Response
    {
        $request = $this->request
            ->post()
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /**
     * Retrieve an existing team for the given id
     * @see https://tempo-io.github.io/tempo-api-docs/#teams
     */
    public function getTeam(string $id): Response
    {
        $request = $this->request
            ->get($id);

        return $this->tempoApiClient->send($request);
    }

    /** Update an existing team for the given id
     * @example {
     *   "name": "Team-A",
     *   "summary": "This is the A team",
     *   "leadAccountId": "1111aaaa2222bbbb3333cccc",
     *   "programId": 42
     *  }
     */
    public function putTeam(string $id, array $body = []): Response
    {
        $request = $this->request
            ->put($id)
            ->withJsonBody($body);

        return $this->tempoApiClient->send($request);
    }

    /** Delete an existing team */
    public function deleteTeam(string $id): Response
    {
        $request = $this->request
            ->delete($id);

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all the links for this team */
    public function getLinks(string $id): Response
    {
        $request = $this->request
            ->get(sprintf('%s/links', $id));

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all the members for this team with their active membership */
    public function getMembers(string $id): Response
    {
        $request = $this->request
            ->get(sprintf('%s/members', $id));

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve the member's active membership for this team */
    public function getMemberByTeamIdAndAccountId(string $id, string $accountId): Response
    {
        $request = $this->request
            ->get(sprintf('%s/members/%s', $id, $accountId));

        return $this->tempoApiClient->send($request);

    }

    /** Retrieve member's memberships for this team */
    public function getMembershipByTeamIdAndAccountId(string $id, string $accountId): Response
    {
        $request = $this->request
            ->get(sprintf('%s/members/%s/memberships', $id, $accountId));

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve all the permissions for this team */
    public function getPermissions(string $id): Response
    {
        $request = $this->request
            ->get(sprintf('%s/permissions', $id));

        return $this->tempoApiClient->send($request);
    }

    /** Retrieve a specific permission belonging to the team  */
    public function getPermissionsByKey(string $id, string $key): Response
    {
        $request = $this->request
            ->get(sprintf('%s/permissions/%s', $id, $key));

        return $this->tempoApiClient->send($request);
    }

    /** ######## EXTENDED FEATURES ########## */

    /** Retrieve all members by team key */
    public function getAllMembersBy(string $key): array
    {
        $teams = $this->getTeams();

        /** @var TeamsCollection $teamsCollection */
        $teamsCollection = $teams->toObject(TeamsCollection::class);
        $tempoTeam = $teamsCollection->getTeam($key);

        if ($tempoTeam === null) {
            return [];
        }

        /** @var TeamMembers $teamMembers */
        $teamMembers = $tempoTeam->getMembers();
        $link = str_replace($this->tempoApiClient->getFullBaseUri(), '/', $teamMembers->getSelf());
        $request = RequestFactory::startsWith()->get($link);
        $response = $this->tempoApiClient->send($request);

        /** @var MembersCollection $members */
        $members = $response->toObject(MembersCollection::class);

        return $members->getMembersNames();
    }

}