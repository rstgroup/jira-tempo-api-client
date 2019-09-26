<?php

namespace JiraTempoApi\HttpClient;

use JiraTempoApi\HttpClient\Formatter\PathFormatter;
use KHerGe\JSON\JSON;

class Request
{
    /** @see  https://github.com/symfony/symfony/blob/4.3/src/Symfony/Component/HttpFoundation/Request.php */
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PURGE = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    /** @var string */
    private $basePath;

    /** @var string */
    private $method;

    /** @var string */
    private $path;

    /** @var array|string[] */
    private $headers;

    /** @var array|string[] */
    private $parameters;

    /** @var array|string[] */
    private $body;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->method = self::METHOD_GET;
        $this->headers = [];
        $this->parameters = [];
        $this->body = [];
    }

    public static function create($basePath)
    {
        return new self($basePath);
    }

    /** @return Request */
    public function head($path = '')
    {
        return $this->httpMethod(self::METHOD_HEAD, $path);
    }

    /** @return Request */
    public function get($path = '')
    {
        return $this->httpMethod(self::METHOD_GET, $path);
    }

    /** @return Request */
    public function post($path = '')
    {
        return $this->httpMethod(self::METHOD_POST, $path);
    }

    /** @return Request */
    public function put($path = '')
    {
        return $this->httpMethod(self::METHOD_PUT, $path);
    }

    /** @return Request */
    public function patch($path = '')
    {
        return $this->httpMethod(self::METHOD_PATCH, $path);
    }

    /** @return Request */
    public function delete($path = '')
    {
        return $this->httpMethod(self::METHOD_DELETE, $path);
    }

    /** @return Request */
    public function purge($path = '')
    {
        return $this->httpMethod(self::METHOD_PURGE, $path);
    }

    /** @return Request */
    public function options($path = '')
    {
        return $this->httpMethod(self::METHOD_OPTIONS, $path);
    }

    /** @return Request */
    public function trace($path = '')
    {
        return $this->httpMethod(self::METHOD_TRACE, $path);
    }

    /** @return Request */
    public function connect($path = '')
    {
        return $this->httpMethod(self::METHOD_CONNECT, $path);
    }

    private function httpMethod($method, $path = '')
    {
        $request = clone $this;
        $request->method = $method;
        $request->path = $path;

        return $request;
    }

    /** @param array|string[] $parameters
     * @return Request
     */
    public function withParameters($parameters = [])
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function withBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function withJsonBody($body = [])
    {
        $json = new JSON();
        $this->body = $json->encode($body);

        return $this;
    }

    public function withHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function withHeaders($headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function method()
    {
        return $this->method;
    }

    public function path($prefixPath = '')
    {
        $restPath = PathFormatter::format(sprintf('/%s', $this->path));
        $prefixPath = PathFormatter::format(sprintf('/%s', $prefixPath));
        return sprintf('%s%s%s', $prefixPath, $this->basePath, $restPath);
    }

    public function body()
    {
        return $this->body;
    }

    public function hasBody()
    {
        return $this->body !== [];
    }

    public function parameters()
    {
        return $this->parameters;
    }

    public function hasParameters()
    {
        return $this->parameters !== [];
    }

    public function headers()
    {
        return $this->headers;
    }
}