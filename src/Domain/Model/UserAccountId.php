<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

use InvalidArgumentException;

class UserAccountId
{
    /** @var string */
    private $username;

    /** @var string */
    private $accountId;

    public function __construct(?array $userAccountIdArray)
    {
        if ($userAccountIdArray === null) {
            throw new InvalidArgumentException('No user account id found');
        }

        $this->username = $userAccountIdArray['username'] ?? null;
        $this->accountId = $userAccountIdArray['accountId'] ?? null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }
}