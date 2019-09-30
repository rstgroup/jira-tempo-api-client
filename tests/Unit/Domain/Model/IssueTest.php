<?php

namespace Tests\Unit\Domain\Model;

use JiraTempoApi\Domain\Model\Issue;
use Tests\Unit\UnitTestCase;

class IssueTest extends UnitTestCase
{
    /** @test */
    public function thatNewIssueObjectHasCorrectAttributes()
    {
        $issue = new Issue('http://tempo.page.io/core/v/3/', 'TASK-1234', 91203142);

        $this->assertEquals('http://tempo.page.io/core/v/3/', $issue->getSelf());
        $this->assertEquals('TASK-1234', $issue->getKey());
    }

    /** @test */
    public function thatMethodCreateReturnsIssueCreatedFromArray()
    {
        $issueArray = [
            'self' => 'http://tempo.page.io/core/v/3/',
            'key' => 'TASK-1234',
            'id' => 91203142
        ];
        $issue = Issue::create($issueArray);

        $this->assertEquals('http://tempo.page.io/core/v/3/', $issue->getSelf());
        $this->assertEquals('TASK-1234', $issue->getKey());
    }

    /** @test */
    public function thatMethodCreateReturnsIssueCreatedFromStandardClassObject()
    {
        $issueArray = (object) [
            'self' => 'http://tempo.page.io/core/v/3/',
            'key' => 'TASK-1234',
            'id' => 91203142
        ];
        $issue = Issue::create($issueArray);

        $this->assertEquals('http://tempo.page.io/core/v/3/', $issue->getSelf());
        $this->assertEquals('TASK-1234', $issue->getKey());
    }

}
