<?php

namespace JiraTempoApi\Domain\Model;

class UserAccountIds
{
    /** @var UserAccountId[] */
    private $userAccountsIds;

    public function __construct($userAccountsIds = [])
    {
        if ($userAccountsIds === null) {
            $userAccountsIds = [];
        }
        $this->userAccountsIds = array_map(
            function ($object) {
                return new UserAccountId((array)$object);
            },
            (array) $userAccountsIds
        );
    }

    public static function create($userAccountsIds = [])
    {
        return new self($userAccountsIds);
    }

    public function getUserAccountsIds()
    {
        reset($this->userAccountsIds);

        return $this->userAccountsIds;
    }

    public function getFirst()
    {
        reset($this->userAccountsIds);
        $first = current($this->userAccountsIds);
        if ($first === false) {
            return null;
        }

        return $first;
    }

    public function getLast()
    {
        $last = end($this->userAccountsIds);
        if ($last === false) {
            return null;
        }

        return $last;
    }
}