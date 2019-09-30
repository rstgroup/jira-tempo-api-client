<?php

namespace Tests\Unit\Domain\Model;

use JiraTempoApi\Domain\Model\Issue;
use JiraTempoApi\Domain\Model\UserWorklogs;
use JiraTempoApi\HttpClient\Response;
use PHPUnit_Framework_MockObject_MockObject;
use Tests\Unit\UnitTestCase;

class UserWorklogsTest extends UnitTestCase
{
    /** @var array */
    private $responseData;

    /** @var Response|PHPUnit_Framework_MockObject_MockObject */
    private $responseMock;

    protected function setUp()
    {
        parent::setUp();
        $this->responseData = [
            'results' => [
                [
                    'issue' => [
                        'key' => 'JIRA-1234',
                        'self' => 'http://jira.example.com/JIRA-1234',
                        'id' => 1234,
                    ],
                ],
                [
                    'issue' => [
                        'key' => 'JIRA-4567',
                        'self' => 'http://jira.example.com/JIRA-4567',
                        'id' => 4567,
                    ],
                ],
                [
                    'issue' => [
                        'key' => 'JIRA-4567',
                        'self' => 'http://jira.example.com/JIRA-4567',
                        'id' => 4567,
                    ],
                ]
            ],
        ];

        $this->responseMock = $this->createPartialMock(Response::class, [
            'getBody',
        ]);

        $this->responseMock
            ->method('getBody')
            ->willReturn(json_encode($this->responseData));
    }

    /** @test */
    public function whenResponseHasDuplicateIssuesThenUserWorklogsShouldReturnedIssuesWithoutDuplicates()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $this->assertCount(2, $workWorklogs->getIssues());
    }

    /** @test */
    public function whenResponseHasResultsThenAllResultsShouldBeReturned()
    {
         /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $this->assertCount(3, $workWorklogs->getResults());
    }

    /** @test */
    public function thatGetIssuesReturnsIssuesObjects()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $this->assertInstanceOf(Issue::class, $workWorklogs->getIssues()[0]);
    }

    /** @test */
    public function thatGetIssuesReturnsCachedResults()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $issues = $workWorklogs->getIssues();
        $this->assertEquals($issues, $workWorklogs->getIssues());
    }
}
