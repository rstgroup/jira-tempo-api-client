<?php

namespace JiraTempoApi\Domain\Model;

class UserAccountIds
{
    /** @var array|UserAccountId[] */
    private $userAccountsIds;

    public function __construct($userAccountsIds = [])
    {
        $this->userAccountsIds = $userAccountsIds;
    }

    public static function create($userAccountsIds = [])
    {
        return new self($userAccountsIds);
    }

    public function getUserAccountsIds()
    {
        reset($this->userAccountsIds);

        return array_map(
            function ($object) {
                return new UserAccountId((array)$object);
            },
            $this->userAccountsIds
        );
    }

    public function getFirst()
    {
        reset($this->userAccountsIds);
        $first = current($this->userAccountsIds);
        if ($first === false) {
            return null;
        }

        return new UserAccountId((array)$first);
    }

    public function getLast()
    {
        $last = end($this->userAccountsIds);
        if ($last === false) {
            return null;
        }

        return new UserAccountId((array)$last);
    }
}