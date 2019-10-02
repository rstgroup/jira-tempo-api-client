<?php

namespace Tests\Unit\Domain\Model;

use JiraTempoApi\Domain\Model\Issue;
use JiraTempoApi\Domain\Model\UserWorklog;
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
                    'billableSeconds' => 1234,
                    'description' => 'Working on issue JIRA-1234',
                    'issue' => [
                        'key' => 'JIRA-1234',
                        'self' => 'http://jira.example.com/JIRA-1234',
                        'id' => 1234,
                    ],
                ],
                [
                    'billableSeconds' => 4567,
                    'description' => 'Working on issue JIRA-4567',
                    'issue' => [
                        'key' => 'JIRA-4567',
                        'self' => 'http://jira.example.com/JIRA-4567',
                        'id' => 4567,
                    ],
                ],
                [
                    'billableSeconds' => 4567,
                    'description' => 'Working on issue JIRA-4567',
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

    /** @test */
    public function thatGetWorklogsReturnsAllWorklogs()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $worklogs = $workWorklogs->getWorklogs();
        $this->assertCount(3, $worklogs);
    }

    /** @test */
    public function thatGetWorklogsReturnsCachedResults()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $worklogs = $workWorklogs->getWorklogs();
        $this->assertEquals($worklogs, $workWorklogs->getWorklogs());
    }

    /** @test */
    public function thatGetGroupedWorklogsByIssueReturnsWorklogsGropedByIssueKey()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $worklogs = $workWorklogs->getGroupedWorklogsByIssues();

        $this->assertCount(2, $worklogs);
        $this->assertArrayHasKey('JIRA-1234', $worklogs);
        $this->assertArrayHasKey('JIRA-4567', $worklogs);
        $this->assertCount(2, $worklogs['JIRA-4567']);
    }

    /** @test */
    public function thatReturnedWorklogsHasDescriptionAndTime()
    {
        /** @var UserWorklogs $workWorklogs */
        $workWorklogs = $this->responseMock->toObject(UserWorklogs::class);
        $worklogs = $workWorklogs->getGroupedWorklogsByIssues();

        $this->assertArrayHasKey('JIRA-1234', $worklogs);
        $issueWorklogs = $worklogs['JIRA-1234'];
        /** @var UserWorklog $worklog */
        $worklog = $issueWorklogs[0];
        $this->assertEquals('Working on issue JIRA-1234', $worklog->getDescription());
        $this->assertEquals(1234, $worklog->getTime());
    }
}
