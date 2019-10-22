<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;
use stdClass;

class TempoTeam extends HyperLinked
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $summary;

    /** @var TempoLead */
    private $lead;

    /** @var TeamProgram */
    private $program;

    /** @var TeamSelfLinks */
    private $links;

    /** @var TeamMembers */
    private $members;

    /** @var TeamPermissions */
    private $permissions;

    public static function createFromObject(stdClass $tempoTeamObject): HyperLinked
    {
        $tempoTeam = parent::create($tempoTeamObject);
        $tempoTeam->id = $tempoTeamObject->id ?? 0;
        $tempoTeam->name = $tempoTeamObject->name ?? '';
        $tempoTeam->summary = $tempoTeamObject->summary ?? '';
        $tempoTeam->lead = isset($tempoTeamObject->lead) ? TempoLead::createFromObject($tempoTeamObject->lead) : null;
        $tempoTeam->program = isset($tempoTeamObject->program) ? TeamProgram::createFromObject($tempoTeamObject->program) : null;
        $tempoTeam->links = isset($tempoTeamObject->links) ? TeamSelfLinks::createFromObject($tempoTeamObject->links) : null;
        $tempoTeam->members = isset($tempoTeamObject->members) ? TeamMembers::createFromObject($tempoTeamObject->members) : null;
        $tempoTeam->permissions = isset($tempoTeamObject->permissions) ? TeamPermissions::createFromObject($tempoTeamObject->permissions) : null;

        return $tempoTeam;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getLead(): TempoLead
    {
        return $this->lead;
    }

    public function getProgram(): TeamProgram
    {
        return $this->program;
    }

    public function getLinks(): TeamSelfLinks
    {
        return $this->links;
    }

    public function getMembers(): TeamMembers
    {
        return $this->members;
    }

    public function getPermissions(): TeamPermissions
    {
        return $this->permissions;
    }
}