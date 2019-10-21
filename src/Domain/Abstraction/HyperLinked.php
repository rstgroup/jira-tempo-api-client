<?php

namespace JiraTempoApi\Domain\Abstraction;

abstract class HyperLinked
{
    protected $self;

    public function getSelf()
    {
        return $this->self;
    }

    public static function create($hyperLinked)
    {
        $newHyperLinked = new static();
        $newHyperLinked->self = isset($hyperLinked->self) ? $hyperLinked->self : '';

        return $newHyperLinked;
    }
}