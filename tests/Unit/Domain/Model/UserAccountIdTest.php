<?php

namespace Tests\Unit\Domain\Model;

use Exception;
use InvalidArgumentException;
use JiraTempoApi\Domain\Model\UserAccountId;
use Tests\Unit\UnitTestCase;

class UserAccountIdTest extends UnitTestCase
{

    /** @test */
    public function whenUserAccountIdHasNoUsernameAndAccountIdThenObjectHasNullProperties()
    {
        $userAccountId = new UserAccountId([]);
        $this->assertNull($userAccountId->getUsername());
        $this->assertNull($userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasOnlyUsernameThenOnlyUsernameShouldReturnsValue()
    {
        $userAccountId = new UserAccountId([
            'username' => 'username'
        ]);
        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertNull($userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasOnlyAccountIdThenOnlyAccountIdShouldReturnsValue()
    {
        $userAccountId = new UserAccountId([
            'accountId' => md5('accountId')
        ]);
        $this->assertNull($userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasAllParametersThenBothParametersShouldHaveValue()
    {
        $userAccountId = new UserAccountId([
            'username' => 'username',
            'accountId' => md5('accountId')
        ]);
        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasNoAnyArrayThenThrowsAnException()
    {
        $this->expectException(InvalidArgumentException::class);

        new UserAccountId(null);
    }

    /** @test */
    public function whenUserAccountIdHasStandardClassObjectThenObjectShouldCreated()
    {
        $userAccountId = new UserAccountId((object) ['username' => 'username', 'accountId' => md5('accountId')]);

        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }
}
