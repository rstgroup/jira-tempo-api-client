<?php

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TempoLead extends HyperLinked
{
    /** @var string */
    private $accountId;

    /** @var string */
    private $displayName;

    public static function createFromObject($lead)
    {
        $tempoLead = parent::create($lead);
        $tempoLead->accountId = isset($lead->accountId) ? $lead->accountId : '';
        $tempoLead->displayName = isset($lead->displayName) ? $lead->displayName : '';

        return $tempoLead;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
}