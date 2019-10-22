<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Abstraction;

use stdClass;

abstract class HyperLinked
{
    /** @var string */
    protected $self;

    public function getSelf(): string
    {
        return $this->self;
    }

    /**
     * @param array|stdClass|object $hyperLinked
     * @return HyperLinked
     */
    public static function create($hyperLinked): HyperLinked
    {
        $newHyperLinked = new static();
        $hyperLinked = (object) $hyperLinked;
        $newHyperLinked->self = $hyperLinked->self ?? '';

        return $newHyperLinked;
    }
}