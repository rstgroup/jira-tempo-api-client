<?php

namespace JiraTempoApi\Domain\Model;

class TeamsCollection
{
    /** @var array */
    private $results;

    /** @return array */
    public function getResults()
    {
        return $this->results;
    }

    public function getTeam($teamName)
    {
        foreach ($this->results as $result) {
            $tempoTeam = TempoTeam::createFromObject($result);
            if (strtolower($tempoTeam->getName()) === strtolower($teamName)) {
                return $tempoTeam;
            }
        }

        return null;
    }
}