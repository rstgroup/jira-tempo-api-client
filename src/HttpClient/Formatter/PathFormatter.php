<?php

namespace JiraTempoApi\HttpClient\Formatter;

class PathFormatter
{
    /** @return string */
    public static function format($path)
    {
        return preg_replace('~/+~', '/', $path);
    }
}