<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

class Request
{
    protected $globals;
    protected $server;
    protected $request;
    protected $post;
    protected $get;
    protected $files;
    protected $env;
    protected $cookie;
    protected $session;
    protected $params=[];
    protected $body;

    public function __construct($globals, $server, $request, $post, $get, $files, $env, $cookie, $session)
    {
        $this->globals = $globals;
        $this->server = $server;
        $this->request = $request;
        $this->post = $post;
        $this->get = $get;
        $this->files = $files;
        $this->env = $env;
        $this->cookie = $cookie;
        $this->session = $session;
        $this->body = file_get_contents('php://input');
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

    public function getPostJsonValue(string $name): string
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
