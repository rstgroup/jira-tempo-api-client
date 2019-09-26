<?php

namespace JiraTempoApi\Domain\Model;

class UserAccountId
{
    /** @var string */
    private $username;

    /** @var string */
    private $accountId;

    public function __construct($userAccountIdArray)
    {
        $this->username = isset($userAccountIdArray['username']) ? $userAccountIdArray['username'] : null;
        $this->accountId = isset($userAccountIdArray['accountId']) ? $userAccountIdArray['accountId'] : null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }
}