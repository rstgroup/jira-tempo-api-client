<?php

namespace Tests\Unit\Repositories\RestApi\V3\JiraApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\HttpClient\Response;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tests\Unit\UnitTestCase;

class UsersTest extends UnitTestCase
{

    /** @test */
    public function whenJiraApiClientReturnsResponse()
    {
        $responseBody = json_encode([
            [
                'username' => 'phpunit',
                'accountId' => md5('phpunit'),
            ],
        ]);

        $jiraApiClientMock = $this->createMock(JiraApiClient::class);
        $jiraApiClientMock
            ->method('exec')
            ->willReturn($responseBody);

        $jiraApiClientMock
            ->method('getLog')
            ->willReturnCallback(function () {
                $loggerMock = $this->createMock(Logger::class);
                $loggerMock
                    ->method('info')
                    ->willReturn(null);
                return $loggerMock;
            });

        $users = new Users($jiraApiClientMock);
        $results = $users->getAccountIdsByUserNames(['phpunit']);
        $this->assertEquals(md5('phpunit'), $results[0]->accountId);
    }

    /** @test */
    public function toHttpQueryParameterMethodShouldReturnHttpEncodedUrl()
    {
        $jiraApiClientMock = $this->createMock(JiraApiClient::class);

        $users = new Users($jiraApiClientMock);
        $httpEncodedParameters = $users->toHttpQueryParameter([
            'username' => 'phpunit',
            'filterId' => md5('phpunit'),
        ]);

        $expected = sprintf('?username=phpunit&filterId=%s', md5('phpunit'));
        $this->assertEquals($expected, $httpEncodedParameters);
    }

    /** @test */
    public function toHttpQueryParameterMethodShouldReturnAllDefinedParametersAsFlattenChain()
    {
        $jiraApiClientMock = $this->createMock(JiraApiClient::class);

        $users = new Users($jiraApiClientMock);
        $httpEncodedParameters = $users->toHttpQueryParameter([
            'username' => ['phpunit', 'test', 'mock'],
            'filterId' => md5('phpunit'),
        ]);

        $expected = sprintf('?username=phpunit&username=test&username=mock&filterId=%s', md5('phpunit'));
        $this->assertEquals($expected, $httpEncodedParameters);
    }
}
