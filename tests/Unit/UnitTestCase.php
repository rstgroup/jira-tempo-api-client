<?php

namespace Tests\Unit;

use donatj\MockWebServer\MockWebServer;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class UnitTestCase extends TestCase
{
    use PHPMock;

    protected $namespace;

    protected function setNamespace($namespace = null)
    {
        $this->namespace = $namespace ?: __NAMESPACE__;

        return $this;
    }

    protected function mockFunction($functionName)
    {
        return $this->getFunctionMock($this->namespace, $functionName);
    }
}