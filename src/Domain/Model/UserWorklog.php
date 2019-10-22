<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

class UserWorklog
{
    /** @var string */
    private $issue;

    /** @var string */
    private $description;

    private $time;

    public function __construct(string $issue, string $description, $time)
    {
        $this->issue = $issue;
        $this->description = $description;
        $this->time = $time;
    }

    public function getIssue(): string
    {
        return $this->issue;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTime()
    {
        return $this->time;
    }
}