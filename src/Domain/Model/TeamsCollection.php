<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

class TeamsCollection
{
    /** @var array */
    private $results;

    public function getResults(): array
    {
        return $this->results;
    }

    public function getTeam(string $teamName): ?TempoTeam
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