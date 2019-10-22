<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use JiraTempoApi\Domain\Abstraction\HyperLinked;

class TempoLead extends HyperLinked
{
    /** @var string */
    private $accountId;

    /** @var string */
    private $displayName;

    public static function createFromObject(object $lead): HyperLinked
    {
        $tempoLead = parent::create($lead);
        $tempoLead->accountId = $lead->accountId ?? '';
        $tempoLead->displayName = $lead->displayName ?? '';

        return $tempoLead;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
}