<?php

namespace Tests\Unit\Repositories\RestApi\V3\TempoApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Clients\TempoApiClient;
use JiraTempoApi\HttpClient\Request;
use JiraTempoApi\HttpClient\Response;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use JiraTempoApi\Repositories\RestApi\V3\TempoApi\Teams;
use JiraTempoApi\Repositories\RestApi\V3\TempoApi\Worklogs;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Unit\UnitTestCase;

class TeamsTest extends UnitTestCase
{
    /** @var StreamInterface|PHPUnit_Framework_MockObject_MockObject */
    private $streamInterfaceMock;

    /** @var TempoApiClient|PHPUnit_Framework_MockObject_MockObject */
    private $tempoApiClientMock;

    /** @var ResponseInterface|PHPUnit_Framework_MockObject_MockObject */
    private $responseMock;

    /** @var JiraApiClient|PHPUnit_Framework_MockObject_MockObject */
    private $jiraApiClientMock;

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

        $this->streamInterfaceMock
            ->method('getContents')
            ->willReturn(json_encode([]));
    }

    /** @test */
    public function thatGetTeamsRequestMethodIsGetAndPathIsTeams()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getTeams();

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/teams', $actualResult['path']);
    }

    /** @test */
    public function thatPostTeamsRequestMethodIsPostAndPathIsTeams()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                $actualResult['body'] = $request->body();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->postTeams();

        $this->assertEquals(Request::METHOD_POST, $actualResult['method']);
        $this->assertEquals(json_encode([]), $actualResult['body']);
        $this->assertEquals('/teams', $actualResult['path']);
    }

    /** @test */
    public function thatGetTeamByIdRequestMethodIsGetAndPathIsTeamsWithId()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getTeam('1234');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/teams/1234', $actualResult['path']);
    }

    /** @test */
    public function thatPutTeamByIdRequestMethodIsPutAndPathIsTeamsWithId()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                $actualResult['body'] = $request->body();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->putTeam('1234');

        $this->assertEquals(Request::METHOD_PUT, $actualResult['method']);
        $this->assertEquals(json_encode([]), $actualResult['body']);
        $this->assertEquals('/teams/1234', $actualResult['path']);
    }

    /** @test */
    public function thatDeleteTeamByIdRequestMethodIsDeleteAndPathIsTeamsWithId()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->deleteTeam('1234');

        $this->assertEquals(Request::METHOD_DELETE, $actualResult['method']);
        $this->assertEquals('/teams/1234', $actualResult['path']);
    }

    /** @test */
    public function thatGetLinksRequestMethodIsGetAndPathIsTeamsWithIdAndLinks()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getLinks('1234');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/teams/1234/links', $actualResult['path']);
    }

    /** @test */
    public function thatGetMembersRequestMethodIsGetAndPathIsTeamsWithIdAndMembers()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getMembers('1234');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/teams/1234/members', $actualResult['path']);
    }

    /** @test */
    public function thatGetMemberByTeamIdAndAccountIdRequestMethodIsGetAndPathHasIdMembersAndAccountId()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getMemberByTeamIdAndAccountId('1234', md5('account'));

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals(sprintf('/teams/1234/members/%s', md5('account')), $actualResult['path']);
    }

    /** @test */
    public function thatGetMembershipByTeamIdAndAccountIdRequestMethodIsGetAndPathHasIdMembersAccountIdMemberships()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getMembershipByTeamIdAndAccountId('1234', md5('account'));

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals(
            sprintf('/teams/1234/members/%s/memberships', md5('account')),
            $actualResult['path']
        );
    }

    /** @test */
    public function thatGetPermissionsRequestMethodIsGetAndPathHasIdWithPermissions()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getPermissions('1234');

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals('/teams/1234/permissions', $actualResult['path']);
    }

    /** @test */
    public function thatGetPermissionsByKeyRequestMethodIsGetAndPathHasIdWithPermissionsAndKey()
    {
        $actualResult = [];
        $this->tempoApiClientMock
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (Request $request) use (&$actualResult){
                $actualResult['method'] = $request->method();
                $actualResult['path'] = $request->path();
                return Response::fromResponse($this->responseMock);
            });

        $teamsRepository = new Teams($this->jiraApiClientMock, $this->tempoApiClientMock);
        $teamsRepository->getPermissionsByKey('1234', md5('key'));

        $this->assertEquals(Request::METHOD_GET, $actualResult['method']);
        $this->assertEquals(
            sprintf('/teams/1234/permissions/%s', md5('key')),
            $actualResult['path']
        );
    }
}
