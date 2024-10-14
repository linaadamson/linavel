<?php

namespace Core\Http;
class Request
{
    private $method;
    private $path;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return $_GET ?? [];
    }

    public function getRequestBody()
    {
        $body = [];

        if (in_array($this->method, [self::METHOD_POST, self::METHOD_PUT, self::METHOD_PATCH])) {
            $body = $this->getJsonBody();
        }

        return $body;
    }

    private function getJsonBody()
    {
        $body = file_get_contents('php://input');
        $decoded = json_decode($body, true);
        return $decoded ?? [];
    }
}