<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Model;

use Exception;
use InvalidArgumentException;
use JiraTempoApi\Domain\Model\UserAccountId;
use Tests\Unit\UnitTestCase;

class UserAccountIdTest extends UnitTestCase
{

    /** @test */
    public function whenUserAccountIdHasNoUsernameAndAccountIdThenObjectHasNullProperties(): void
    {
        $userAccountId = new UserAccountId([]);
        $this->assertNull($userAccountId->getUsername());
        $this->assertNull($userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasOnlyUsernameThenOnlyUsernameShouldReturnsValue(): void
    {
        $userAccountId = new UserAccountId([
            'username' => 'username'
        ]);
        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertNull($userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasOnlyAccountIdThenOnlyAccountIdShouldReturnsValue(): void
    {
        $userAccountId = new UserAccountId([
            'accountId' => md5('accountId')
        ]);
        $this->assertNull($userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasAllParametersThenBothParametersShouldHaveValue(): void
    {
        $userAccountId = new UserAccountId([
            'username' => 'username',
            'accountId' => md5('accountId')
        ]);
        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }

    /** @test */
    public function whenUserAccountIdHasNoAnyArrayThenThrowsAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new UserAccountId(null);
    }

    /** @test */
    public function whenUserAccountIdHasStandardClassObjectThenObjectShouldCreated(): void
    {
        $userAccountId = new UserAccountId(['username' => 'username', 'accountId' => md5('accountId')]);

        $this->assertEquals('username', $userAccountId->getUsername());
        $this->assertEquals(md5('accountId'), $userAccountId->getAccountId());
    }
}
