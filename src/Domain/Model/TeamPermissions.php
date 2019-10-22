<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamPermissions extends HyperLinked
{
    public static function createFromObject(object $permissions): HyperLinked
    {
        return parent::create($permissions);
    }
}