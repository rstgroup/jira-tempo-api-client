<?php
declare(strict_types=1);

namespace JiraTempoApi\HttpClient\Factory;

use JiraTempoApi\HttpClient\Request;

class RequestFactory
{
    /** @return Request */
    public static function startsWith(string $basePath = ''): Request
    {
        return new Request($basePath);
    }
}