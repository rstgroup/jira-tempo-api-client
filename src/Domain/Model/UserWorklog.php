<?php

namespace JiraTempoApi\Domain\Model;

class UserWorklog
{
    private $issue;

    private $description;

    private $time;

    public function __construct($issue, $description, $time)
    {
        $this->issue = $issue;
        $this->description = $description;
        $this->time = $time;
    }

    public function getIssue()
    {
        return $this->issue;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTime()
    {
        return $this->time;
    }
}