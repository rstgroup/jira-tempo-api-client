<?php
declare(strict_types=1);

namespace JiraTempoApi\Domain\Model;

class UserAccountIds
{
    /** @var UserAccountId[] */
    private $userAccountsIds;

    public function __construct(?array $userAccountsIds = [])
    {
        if ($userAccountsIds === null) {
            $userAccountsIds = [];
        }
        $this->userAccountsIds = array_map(
            static function ($object) {
                return new UserAccountId((array)$object);
            },
            (array) $userAccountsIds
        );
    }

    public static function create(?array $userAccountsIds = []): UserAccountIds
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