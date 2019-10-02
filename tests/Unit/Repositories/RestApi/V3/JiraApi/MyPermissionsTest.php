<?php

namespace Tests\Unit\Repositories\RestApi\V3\JiraApi;

use JiraTempoApi\Clients\JiraApiClient;
use JiraTempoApi\Repositories\RestApi\V3\JiraApi\MyPermissions;
use Monolog\Logger;
use Tests\Unit\UnitTestCase;

class MyPermissionsTest extends UnitTestCase
{
    /** @test */
    public function thatMyPermissionsRequestReturnsExpectedResponse()
    {
        $responseBody = json_encode([
            'permissions' =>
                [
                    'BROWSE_PROJECTS' =>
                        [
                            'id' => '10',
                            'key' => 'BROWSE_PROJECTS',
                            'name' => 'Browse Projects',
                            'type' => 'PROJECT',
                            'description' => 'Ability to browse projects and the issues within them.',
                            'havePermission' => true,
                        ],
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

        $users = new MyPermissions($jiraApiClientMock);
        $results = $users->getMyPermissions(['BROWSE_PROJECTS']);
        $this->assertTrue(isset($results->permissions));
        $this->assertTrue(isset($results->permissions->BROWSE_PROJECTS));
        $this->assertTrue(isset($results->permissions->BROWSE_PROJECTS->havePermission));
        $this->assertTrue($results->permissions->BROWSE_PROJECTS->havePermission);
    }
}
