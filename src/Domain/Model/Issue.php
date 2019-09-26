<?php

namespace JiraTempoApi\Domain\Model;

class Issue
{
    /** @var string */
    private $self;

    /** @var string */
    private $key;

    /** @var string */
    private $id;

    public function __construct($self, $key, $id)
    {
        $this->self = $self;
        $this->key = $key;
        $this->id = $id;
    }

    public static function create($issue)
    {
        if(is_object($issue)) {
            $self = isset($issue->self) ? $issue->self : null;
            $key= isset($issue->key) ? $issue->key : null;
            $id= isset($issue->id) ? $issue->id : null;
        } else {
            $self = isset($issue['self']) ? $issue['self'] : null;
            $key= isset($issue['key']) ? $issue['key'] : null;
            $id = isset($issue['id']) ? $issue['id'] : null;
        }

        return new self($self, $key, $id);
    }

    /** @return string url */
    public function getSelf()
    {
        return $this->self;
    }

    /** @return string jira issue key */
    public function getKey()
    {
        return $this->key;
    }
}