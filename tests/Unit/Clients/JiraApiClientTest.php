<?php

namespace Tests\Unit\Clients;

use Dotenv\Exception\ValidationException;
use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use JsonMapper;
use Psr\Log\LoggerInterface;
use Tests\Unit\UnitTestCase;
use Tests\Utils\ArrayConfigurationFactory;

class JiraApiClientTest extends UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function thatJiraApiClientBuildsVersion3ApiPathWithHost()
    {
        $curl = curl_init();

        $this->setNamespace('JiraRestApi');
        $this
            ->mockFunction('curl_init')
            ->expects($this->any())
            ->willReturn($curl);
        $this
            ->mockFunction('curl_exec')
            ->expects($this->any())
            ->willReturn('something');
        $this
            ->mockFunction('curl_getinfo')
            ->expects($this->any())
            ->willReturn(200);

        $jiraApiClient = new JiraApiClient(
            ArrayConfigurationFactory::create('http://my.jira.com')
        );
        $jiraApiClient->exec('//user////bulk/////migration');

        $info = curl_getinfo($curl);
        $this->assertArrayHasKey('url', $info);

        $this->assertEquals('http://my.jira.com/rest/api/3/user/bulk/migration',  $info['url']);
    }

    /** @test */
    public function thatJiraApiClientUsedInjectingCurlObject()
    {
        $curl = curl_init();

        $this->setNamespace('JiraRestApi');
        $this
            ->mockFunction('curl_exec')
            ->expects($this->any())
            ->willReturn('something');
        $this
            ->mockFunction('curl_getinfo')
            ->expects($this->any())
            ->willReturn(200);

        $jiraApiClient = new JiraApiClient(
            ArrayConfigurationFactory::create('http://my.jira.com'),
            null,
            './',
            $curl
        );
        $jiraApiClient->exec('//user////bulk/////migration');

        $info = curl_getinfo($curl);
        $this->assertArrayHasKey('url', $info);

        $this->assertEquals('http://my.jira.com/rest/api/3/user/bulk/migration', $info['url']);
    }

    /** @test */
    public function thatMethodGetUsersReturnsUsersJiraRepository()
    {
        $jiraApiClient = new JiraApiClient();

        $this->assertInstanceOf(Users::class, $jiraApiClient->getUsers());
    }

    /** @test */
    public function thatMethodGetUsersReturnsCashedRepository()
    {
        $jiraApiClient = new JiraApiClient();
        $users = $jiraApiClient->getUsers();
        $users2 = $jiraApiClient->getUsers();

        $this->assertEquals($users, $users2);
    }

    /** @test */
    public function thatMethodGetJsonMapperReturnsJsonMapper()
    {
        $jiraApiClient = new JiraApiClient();

        $this->assertInstanceOf(JsonMapper::class, $jiraApiClient->getJsonMapper());
    }

    /** @test */
    public function thatGetLogReturnsLogger()
    {
        $jiraApiClient = new JiraApiClient();

        $this->assertInstanceOf(LoggerInterface::class, $jiraApiClient->getLog());
    }

    /** @test */
    public function whenJiraHostIsNotDefinedThatThrowException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('JIRA_HOST');
        $this->expectExceptionMessage('JIRA_USER');
        $this->expectExceptionMessage('JIRA_PASS');

        unset($_ENV['JIRA_HOST']);
        unset($_ENV['JIRA_USER']);
        unset($_ENV['JIRA_PASS']);

        $this
            ->setNamespace('Dotenv\Environment\Adapter')
            ->mockFunction('getenv')
            ->expects($this->any())
            ->willReturn(false);

        new JiraApiClient(null, null, '/not/existing/path');
    }
}
