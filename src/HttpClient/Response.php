<?php

namespace JiraTempoApi\HttpClient;

use Exception;
use JiraTempoApi\Domain\Model\UserWorklogs;
use JsonMapper;
use KHerGe\JSON\JSON;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var ResponseInterface */
    private $response;

    /** @var Exception */
    private $exception;

    /** @var string */
    private $body;

    public static function fromResponse(ResponseInterface $response)
    {
        $createdResponse = new self();
        $createdResponse->response = $response;

        return $createdResponse;
    }

    public static function fromException(Exception $exception)
    {
        $newResponse = new self();
        $newResponse->exception = $exception;

        return $newResponse;
    }

    public function getBody()
    {
        if($this->response != null && $this->body === null) {
            $this->body = $this->response->getBody()->getContents();
        }

        return $this->body;
    }

    public function toArray()
    {
        $json = new JSON();
        return (array) $json->decode($this->getBody());
    }

    public function toObject($className)
    {
        $json = new JSON();
        $mapper = new JsonMapper();
        $array = (array) $json->decode($this->getBody());
        $object = (object) $json->decode($this->getBody());
        $mapper->bIgnoreVisibility = true;
        return $mapper->map($object, new $className(...array_values($array)));
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getCode()
    {
        if($this->response != null) {
            return $this->response->getStatusCode();
        }

        return 500;
    }
}