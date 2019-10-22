<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Model;

use JiraTempoApi\Domain\Model\UserAccountIds;
use Tests\Unit\UnitTestCase;

class UserAccountIdsTest extends UnitTestCase
{

    /** @test */
    public function whenNoDataPassedThenCreatedUserAccountsIdsHasEmptyData(): void
    {
        $userAccountIds = UserAccountIds::create();
        $this->assertEmpty($userAccountIds->getUserAccountsIds());
    }

    /** @test */
    public function whenSomeDataPassedThenUserAccountsIdsShouldHaveUserAccountIdElements(): void
    {
        $userAccountIds = UserAccountIds::create([
            ['username' => 'test', 'accountId' => md5('test')]
        ]);
        $this->assertNotEmpty($userAccountIds->getUserAccountsIds());
        $this->assertEquals('test', $userAccountIds->getFirst()->getUsername());
        $this->assertEquals(md5('test'), $userAccountIds->getFirst()->getAccountId());
    }

    /** @test */
    public function whenNoDataPassedThenCreatedUserAccountsIdsHasNullableFirstElement(): void
    {
        $userAccountIds = UserAccountIds::create();
        $this->assertNull($userAccountIds->getFirst());
    }

    /** @test */
    public function whenNoDataPassedThenCreatedUserAccountsIdsHasNullableLastElement(): void
    {
        $userAccountIds = UserAccountIds::create();
        $this->assertNull($userAccountIds->getLast());
    }

    /** @test */
    public function whenOnlyOneElementPassedThenUserAccountsIdsFirstElementShouldBeTheSameAsLastElement(): void
    {
        $userAccountIds = UserAccountIds::create([
            ['username' => 'test', 'accountId' => md5('test')]
        ]);
        $this->assertNotEmpty($userAccountIds->getUserAccountsIds());
        $this->assertEquals($userAccountIds->getLast(), $userAccountIds->getFirst());
    }

    /** @test */
    public function whenNullPassedThenCreatedUserAccountsIdsHasEmptyData(): void
    {
        $userAccountIds = UserAccountIds::create(null);
        $this->assertEmpty($userAccountIds->getUserAccountsIds());
    }

    /** @test */
    public function whenPassedDataIsStandardClassObjectThenShouldBeCastToArray(): void
    {
        $userAccountIds = UserAccountIds::create([
            (object) ['username' => 'test', 'accountId' => md5('test')]
        ]);
        $this->assertNotEmpty($userAccountIds->getUserAccountsIds());
    }
}
