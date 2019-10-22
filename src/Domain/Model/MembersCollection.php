<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

class MembersCollection
{
    /** @var array */
    private $results = [];

    public function getResults(): array
    {
        return $this->results;
    }

    public function getMembersNames(): array
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