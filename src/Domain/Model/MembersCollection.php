<?php

namespace JiraTempoApi\Domain\Model;

class MembersCollection
{
    /** @var array */
    private $results = [];

    public function getResults()
    {
        return $this->results;
    }

    public function getMembersNames()
    {
        $membersNames = [];
        foreach ($this->results as $result) {
            if(isset($result->member->displayName)) {
                $membersNames[] = $result->member->displayName;
            }
        }

        return $membersNames;
    }
}