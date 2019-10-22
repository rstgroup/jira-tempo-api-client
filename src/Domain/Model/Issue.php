<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use stdClass;

class Issue
{
    /** @var string */
    private $self;

    /** @var string */
    private $key;

    /** @var string */
    private $id;

    public function __construct(?string $self, ?string $key, ?int $id)
    {
        $this->self = $self;
        $this->key = $key;
        $this->id = $id;
    }

    /**
     * @param array|stdClass|object $issue
     * @return Issue
     */
    public static function create($issue): Issue
    {
        $issue = (object) $issue;
        $self = $issue->self ?? null;
        $key= $issue->key ?? null;
        $id= $issue->id ?? null;

        return new self($self, $key, $id);
    }

    /** @return string url */
    public function getSelf(): string
    {
        return $this->self;
    }

    /** @return string jira issue key */
    public function getKey(): string
    {
        return $this->key;
    }
}