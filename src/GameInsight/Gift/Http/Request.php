<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

use GameInsight\Gift\Http\Exceptions\BadRequest;

class Request
{
    protected $server;
    protected $post;
    protected $get;
    protected $cookie;
    protected $params=[];
    protected $body;

    public function __construct($server=[], $post=[], $get=[], $cookie=[], $body='')
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

    public function getServerValue(string $name): string
    {
        if (!isset($this->server[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in server');
        }
        return $this->server[$name];
    }

    public function getParamValue(string $name): string
    {
        if (!isset($this->params[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in params');
        }
        return $this->params[$name];
    }

    public function getGetValue(string $name): string
    {
        if (!isset($this->get[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in get');
        }
        return $this->get[$name];
    }

    public function getUri(): string
    {
        return $this->getGetValue('path');// from nginx or REQUEST_URI from server
    }

    public function getBodyJsonValue(string $name)
    {
        $jsonBody = json_decode($this->body);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequest('Is not json '.$this->body);
        }
        if (!isset($jsonBody->{$name})) {
            throw new BadRequest('Could not find ' . $name . ' in body');
        }
        return $jsonBody->{$name};
    }

    public function getMethod(): string
    {
        return $this->getServerValue('REQUEST_METHOD');
    }

    public function getHeader(string $name)
    {
        return $this->getServerValue('HTTP_' . $name);
    }

    public function getBody()
    {
        return $this->body;
    }
}
