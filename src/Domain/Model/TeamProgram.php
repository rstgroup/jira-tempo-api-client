<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamProgram extends HyperLinked
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    public static function createFromObject($program)
    {
        $tempoProgram = parent::create($program);
        $tempoProgram->id = isset($program->id) ? $program->id : 0;
        $tempoProgram->name = isset($program->name) ? $program->name : '';

        return $tempoProgram;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}