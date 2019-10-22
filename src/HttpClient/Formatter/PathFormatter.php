<?php
declare(strict_types=1);

namespace JiraTempoApi\HttpClient\Formatter;

class PathFormatter
{
    public static function format(string $path): string
    {
        return preg_replace('~/+~', '/', $path);
    }
}