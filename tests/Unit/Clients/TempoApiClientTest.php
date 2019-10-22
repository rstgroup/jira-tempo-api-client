<?php
declare(strict_types=1);

namespace Tests\Unit\Clients;

use JiraTempoApi\Clients\TempoApiClient;
use Tests\Unit\UnitTestCase;

class TempoApiClientTest extends UnitTestCase
{
    /** @test */
    public function thatCreateMethodReturnsNewClient(): void
    {
        $client = TempoApiClient::create();

        $this->assertInstanceOf(TempoApiClient::class, $client);
    }
}
