<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories\RestApi\V3\JiraApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\Users;
use Monolog\Logger;
use Tests\Unit\UnitTestCase;

class UsersTest extends UnitTestCase
{
    /** @test */
    public function thatResponseFromJiraApiClientContainsCorrectIdForUser(): void
    {
        $responseBody = json_encode([
            [
                'username' => 'phpunit',
                'accountId' => md5('phpunit'),
            ],
        ], JSON_THROW_ON_ERROR, 512);

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
}
