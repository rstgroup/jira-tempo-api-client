<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

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

    public static function createFromObject($tempoTeamObject)
    {
        $tempoTeam = parent::create($tempoTeamObject);
        $tempoTeam->id = isset($tempoTeamObject->id) ? $tempoTeamObject->id : 0;
        $tempoTeam->name = isset($tempoTeamObject->name) ? $tempoTeamObject->name : '';
        $tempoTeam->summary = isset($tempoTeamObject->summary) ? $tempoTeamObject->summary : '';
        $tempoTeam->lead = isset($tempoTeamObject->lead) ? TempoLead::createFromObject($tempoTeamObject->lead) : null;
        $tempoTeam->program = isset($tempoTeamObject->program) ? TeamProgram::createFromObject($tempoTeamObject->program) : null;
        $tempoTeam->links = isset($tempoTeamObject->links) ? TeamSelfLinks::createFromObject($tempoTeamObject->links) : null;
        $tempoTeam->members = isset($tempoTeamObject->members) ? TeamMembers::createFromObject($tempoTeamObject->members) : null;
        $tempoTeam->permissions = isset($tempoTeamObject->permissions) ? TeamPermissions::createFromObject($tempoTeamObject->permissions) : null;

        return $tempoTeam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function getLinks()
    {
        return $this->links;
    }

    /** @return TeamMembers */
    public function getMembers()
    {
        return $this->members;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}