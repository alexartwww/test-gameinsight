<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

class Request
{
    protected $server;
    protected $post;
    protected $get;
    protected $cookie;
    protected $params=[];
    protected $body;

    public function __construct($server, $post, $get, $cookie, $body)
    {
        $this->server = $server;
        $this->post = $post;
        $this->get = $get;
        $this->cookie = $cookie;
        $this->body = $body;
    }

    public function setParams(array $params): Request
    {
        $this->params = $params;
        return $this;
    }

    public function getParamValue(string $name): string
    {
        return $this->params[$name] ?? '';
    }

    public function getPostValue(string $name): string
    {
        return $this->post[$name] ?? '';
    }

    public function getGetValue(string $name): string
    {
        return $this->get[$name] ?? '';
    }

    public function getServerValue(string $name): string
    {
        return $this->server[$name] ?? '';
    }

    public function getPostJsonValue(string $name)
    {
        $jsonBody = json_decode($this->body, true);
        return $jsonBody[$name] ?? '';
    }

    public function getMethod(): string
    {
        return $this->getServerValue('REQUEST_METHOD');
    }

    public function getUri(): string
    {
        return $this->getGetValue('path');// from nginx or REQUEST_URI from server
    }

    public function getHeader(string $name)
    {
        return $this->getServerValue('HTTP_' . $name);
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getBodyAsArray(): array
    {
        return json_decode($this->getBody(),true);
    }
}
