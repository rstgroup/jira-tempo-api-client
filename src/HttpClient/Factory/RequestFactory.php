<?php

namespace JiraTempoApi\HttpClient\Factory;

use JiraTempoApi\HttpClient\Request;

class RequestFactory
{
    /** @return Request */
    public static function startsWith($basePath = '')
    {
        return new Request($basePath);
    }
}