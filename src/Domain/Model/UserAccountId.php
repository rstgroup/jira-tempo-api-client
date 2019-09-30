<?php

namespace JiraTempoApi\Domain\Model;

use InvalidArgumentException;

class UserAccountId
{
    /** @var string */
    private $username;

    /** @var string */
    private $accountId;

    public function __construct($userAccountIdArray)
    {
        if ($userAccountIdArray === null) {
            throw new InvalidArgumentException('No user account id found');
        }
        $userAccountIdArray = (array) $userAccountIdArray;
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