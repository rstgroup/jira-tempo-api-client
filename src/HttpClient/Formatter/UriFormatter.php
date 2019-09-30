<?php

namespace JiraTempoApi\HttpClient\Formatter;

class UriFormatter
{
    /** @return string */
    public static function format($baseUri, $protocol = 'http')
    {
        if (strpos($baseUri, $protocol) > -1) {
            $restPaths = explode(':', $baseUri);
            if(count($restPaths) > 1) {
                $protocol = $restPaths[0];
                $restPath = sprintf('%s', $restPaths[1]);
                $path = PathFormatter::format(sprintf('/%s', $restPath));
                return sprintf('%s:/%s', $protocol, $path);
            }

            return $baseUri;
        }

        $path = PathFormatter::format(sprintf('/%s', $baseUri));
        return sprintf('%s:/%s', $protocol, $path);
    }
}