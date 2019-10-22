<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamMembers extends HyperLinked
{
    public static function createFromObject(object $members): HyperLinked
    {
        return parent::create($members);
    }
}