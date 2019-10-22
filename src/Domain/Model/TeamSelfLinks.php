<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamSelfLinks extends HyperLinked
{
    public static function createFromObject($links): HyperLinked
    {
        return parent::create($links);
    }
}