<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamSelfLinks extends HyperLinked
{
    public static function createFromObject($links)
    {
        return parent::create($links);
    }
}