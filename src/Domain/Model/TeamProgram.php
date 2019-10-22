<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TeamProgram extends HyperLinked
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    public static function createFromObject(object $program): HyperLinked
    {
        $tempoProgram = parent::create($program);
        $tempoProgram->id = $program->id ?? 0;
        $tempoProgram->name = $program->name ?? '';

        return $tempoProgram;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}