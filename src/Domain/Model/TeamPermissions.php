<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamPermissions extends HyperLinked
{
    public static function createFromObject($permissions)
    {
        return parent::create($permissions);
    }
}