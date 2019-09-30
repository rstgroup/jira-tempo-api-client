<?php

namespace Tests\Repositories\RestApi\V3\TempoApi;

use JiraRestApi\User\User;
use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\HttpClient\Request;
use JiraTempoApi\HttpClient\Response;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use JiraTempoApi\Repositories\RestApi\V3\TempoApi\Worklogs;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Unit\UnitTestCase;
use function GuzzleHttp\Psr7\str;

class WorklogsTest extends UnitTestCase
{
    /** @var JiraApiClient|PHPUnit_Framework_MockObject_MockObject */
    private $jiraApiClientMock;

    /** @var TempoApiClient|PHPUnit_Framework_MockObject_MockObject */
    private $tempoApiClientMock;

    /** @var StreamInterface|PHPUnit_Framework_MockObject_MockObject */
    private $streamInterfaceMock;

    /** @var ResponseInterface|PHPUnit_Framework_MockObject_MockObject */
    private $responseMock;

    /** @var array */
    private $worklogsResponse;

    protected function setUp()
    {
        parent::setUp();
        $usersMock = $this->createMock(Users::class);
        $usersMock
            ->method('getAccountIdsByUserNames')
            ->willReturn([
                (object)[
                    'username' => 'phpunit',
                    'accountId' => md5('phpunit'),
                ],
            ]);

        $this->jiraApiClientMock = $this->createMock(JiraApiClient::class);
        $this->jiraApiClientMock
            ->method('getUsers')
            ->willReturn($usersMock);

        $this->tempoApiClientMock = $this->createMock(TempoApiClient::class);

        $this->streamInterfaceMock = $this->createMock(StreamInterface::class);

        $this->responseMock = $this->createMock(ResponseInterface::class);
        $this->responseMock
            ->method('getBody')
            ->willReturn($this->streamInterfaceMock);

        $this->worklogsResponse = [
            [
            'self' => 'https://api.tempo.io/core/3/worklogs/12600',
            'tempoWorklogId' => 126,
            'jiraWorklogId' => 10100,
            'issue' =>
                [
                    'self' => 'https://my-cloud-instance.atlassian.net/rest/api/2/issue/DUM-1',
                    'key' => 'DUM-1',
                ],
            'timeSpentSeconds' => 3600,
            'billableSeconds' => 5200,
            'startDate' => '2017-02-06',
            'startTime' => '20:06:00',
            'description' => 'Investigating a problem with our external database system',
            'createdAt' => '2017-02-06T16:41:41Z',
            'updatedAt' => '2017-02-06T16:41:41Z',
            'author' =>
                [
                    'self' => 'https://my-cloud-instance.atlassian.net/rest/api/2/user?accountId=1111aaaa2222bbbb3333cccc',
                    'accountId' => '1111aaaa2222bbbb3333cccc',
                    'displayName' => 'John Brown',
                ],
            'attributes' =>
                [
                    'self' => 'https://api.tempo.io/core/3/worklogs/126/work-attribute-values',
                    'values' =>
                        [
                            0 =>
                                [
                                    'key' => '_DELIVERED_',
                                    'value' => true,
                                ],
                            1 =>
                                [
                                    'key' => '_EXTERNALREF_',
                                    'value' => 'EXT-44556',
                                ],
                            2 =>
                                [
                                    'key' => '_COLOR_',
                                    'value' => 'red',
                                ],
                        ],
                ],
        ],
            [
                'self' => 'https://api.tempo.io/core/3/worklogs/12600',
                'tempoWorklogId' => 126,
                'jiraWorklogId' => 10100,
                'issue' =>
                    [
                        'self' => 'https://my-cloud-instance.atlassian.net/rest/api/2/issue/DUM-1',
                        'key' => 'DUM-1',
                    ],
                'timeSpentSeconds' => 3600,
                'billableSeconds' => 5200,
                'startDate' => '2017-02-06',
                'startTime' => '20:06:00',
                'description' => 'Investigating a problem with our external database system',
                'createdAt' => '2017-02-06T16:41:41Z',
                'updatedAt' => '2017-02-06T16:41:41Z',
                'author' =>
                    [
                        'self' => 'https://my-cloud-instance.atlassian.net/rest/api/2/user?accountId=1111aaaa2222bbbb3333cccc',
                        'accountId' => '1111aaaa2222bbbb3333cccc',
                        'displayName' => 'John Brown',
                    ],
                'attributes' =>
                    [
                        'self' => 'https://api.tempo.io/core/3/worklogs/126/work-attribute-values',
                        'values' =>
                            [
                                0 =>
                                    [
                                        'key' => '_DELIVERED_',
                                        'value' => true,
                                    ],
                                1 =>
                                    [
                                        'key' => '_EXTERNALREF_',
                                        'value' => 'EXT-44556',
                                    ],
                                2 =>
                                    [
                                        'key' => '_COLOR_',
                                        'value' => 'red',
                                    ],
                            ],
                    ],
            ]
        ];
    }

    /** @test */
    public function whenGetAllWorklogsReturnsListOfWorklogsWithGetMethod()
    {
        $body = [
            [
                "self" => "https://api.tempo.io/core/3/worklogs?from=2017-02-01&to=2017-02-28&offset=0&limit=50",
                "metadata" => [
                    "count" => 18,
                    "offset" => 0,
                    "limit" => 50,
                ],
                "results" => [
                    $this->worklogsResponse[0],
                ],

            ],
        ];
        $actualResult = [];
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['parameters'] = $request->parameters();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogs = $worklogRepository->getWorklogs();

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals([], $actualResult['parameters']);
        $this->assertEquals(json_encode($body), $worklogs->getBody());
    }

    /** @test */
    public function whenPostWorklogsCallThenReturnsNewWorklogAndRequestWasPostMethodWithEmptyBody()
    {
        $body = $this->worklogsResponse[0];
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['body'] = $request->body();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogs = $worklogRepository->postWorklogs();

        $this->assertEquals(Request::METHOD_POST, $actualResult['method']);
        $this->assertEquals(json_encode([]), $actualResult['body']);
        $this->assertEquals(json_encode($body), $worklogs->getBody());
    }

    /** @test */
    public function whenGetWorklogByIdPassedCorrectIdThenReturnsWorklogAndRequestHasGetMethodWithoutAnyQueryParameters()
    {
        $body = $this->worklogsResponse[0];
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['parameters'] = $request->parameters();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogs = $worklogRepository->getWorklogById('126');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals([], $actualResult['parameters']);
        $this->assertEquals(json_encode($body), $worklogs->getBody());
    }

    /** @test */
    public function whenPutWorklogByIdPassedCorrectIdThenReturnsChangedWorkflowForPutMethodRequestAndEmptyBody()
    {
        $body = $this->worklogsResponse[1];
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['body'] = $request->body();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogs = $worklogRepository->putWorklogById('126');

        $this->assertEquals(Request::METHOD_PUT, $actualResult['method']);
        $this->assertEquals(json_encode([]), $actualResult['body']);
        $this->assertEquals(json_encode($body), $worklogs->getBody());
    }


    /** @test */
    public function whenDeleteWorklogByIdPassedCorrectIdThenReturnsSuccessDelete()
    {
        $body = [];
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode($body));

        $this->responseMock
            ->method('getStatusCode')
            ->willReturn(204);

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['body'] = $request->body();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogs = $worklogRepository->deleteWorklogById('126');

        $this->assertEquals(Request::METHOD_DELETE, $actualResult['method']);
        $this->assertEquals([], $actualResult['body']);
        $this->assertEquals(json_encode($body), $worklogs->getBody());
        $this->assertEquals(204, $worklogs->getCode());
    }

    /** @test */
    public function whenGetWorkAttributesValuesCallsThenRequestShouldBeGetAndHasWorkAttributeValuesPostfix()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getWorkAttributesValues('126');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/126/work-attribute-values', $actualResult['path']);
    }

    /** @test */
    public function whenGetWorklogAttributeValuesByKeyShouldBeGetRequestWithCorrectPath()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getWorkAttributesValuesByKey('126', 'key');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/126/work-attribute-values/key', $actualResult['path']);
    }

    /** @test */
    public function whenGetWorklogByJiraWorklogIdThenRequestShouldBeGetAndPrefixedPathWithJira()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getWorklogByJiraWorklogId('1257');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/jira/1257', $actualResult['path']);
    }

    /** @test */
    public function whenGetWorklogByJiraFilterIdThenRequestShouldBeGetAndPrefixedPathWithJiraFilter()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getWorklogByJiraFilterId('1257');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/jira/filter/1257', $actualResult['path']);
    }

    /** @test */
    public function whenGetAllWorklogsByAccountKeyThenRequestShouldBeGetAndPrefixedPathWithAccount()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getAllWorklogsByAccountKey('ACC001');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/account/ACC001', $actualResult['path']);
    }

    /** @test */
    public function whenGetAllWorklogsByProjectKeyThenRequestShouldBeGetAndPrefixedPathWithProject()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getAllWorklogsByProjectKey('PRJ-123');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/project/PRJ-123', $actualResult['path']);
    }

    /** @test */
    public function whenGetAllWorklogsByTeamIdThenRequestShouldBeGetAndPrefixedPathWithTeam()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getAllWorklogsByTeamId('TEAM-123');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/team/TEAM-123', $actualResult['path']);
    }

    /** @test */
    public function whenGetAllWorklogsByUserAccountIdThenRequestShouldBeGetAndPrefixedPathWithUser()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getAllWorklogsByUserAccountId(md5('phpunit'));

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals(sprintf('/worklogs/user/%s', md5('phpunit')), $actualResult['path']);
    }

    /** @test */
    public function whenGetAllWorklogsByIssueKeyThenRequestShouldBeGetAndPrefixedPathWithIssue()
    {
        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));

        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $worklogRepository = new Worklogs($this->jiraApiClientMock, $this->tempoApiClientMock);
        $worklogRepository->getAllWorklogsByIssueKey('JIRA-1234');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/worklogs/issue/JIRA-1234', $actualResult['path']);
    }
}
