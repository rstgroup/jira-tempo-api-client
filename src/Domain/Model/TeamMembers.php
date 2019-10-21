<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamMembers extends HyperLinked
{
    public static function createFromObject($members)
    {
        return parent::create($members);
    }
}