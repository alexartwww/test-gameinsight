<?php
declare(strict_types=1);

namespace GameInsight\Gift\Http;

use GameInsight\Gift\Http\Exceptions\BadRequest;

/**
 * Class Request
 * @package GameInsight\Gift\Http
 */
class Request
{
    /**
     * @var array
     */
    protected $server;
    /**
     * @var array
     */
    protected $post;
    /**
     * @var array
     */
    protected $get;
    /**
     * @var array
     */
    protected $cookie;
    /**
     * @var array
     */
    protected $params=[];
    /**
     * @var string
     */
    protected $body;

    /**
     * Request constructor.
     * @param array $server
     * @param array $post
     * @param array $get
     * @param array $cookie
     * @param string $body
     */
    public function __construct($server=[], $post=[], $get=[], $cookie=[], $body='')
    {
        $this->server = $server;
        $this->post = $post;
        $this->get = $get;
        $this->cookie = $cookie;
        $this->body = $body;
    }

    /**
     * @param array $params
     * @return Request
     */
    public function setParams(array $params): Request
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer(array $server)
    {
        $this->server = $server;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @param array $post
     */
    public function setPost(array $post)
    {
        $this->post = $post;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @param array $get
     */
    public function setGet(array $get)
    {
        $this->get = $get;
    }

    /**
     * @return array
     */
    public function getCookie(): array
    {
        return $this->cookie;
    }

    /**
     * @param array $cookie
     */
    public function setCookie(array $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @param string $name
     * @return string
     * @throws BadRequest
     */
    public function getServerValue(string $name): string
    {
        if (!isset($this->server[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in server');
        }
        return $this->server[$name];
    }

    /**
     * @param string $name
     * @return string
     * @throws BadRequest
     */
    public function getParamValue(string $name): string
    {
        if (!isset($this->params[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in params');
        }
        return $this->params[$name];
    }

    /**
     * @param string $name
     * @return string
     * @throws BadRequest
     */
    public function getGetValue(string $name): string
    {
        if (!isset($this->get[$name])) {
            throw new BadRequest('Could not find ' . $name . ' in get');
        }
        return $this->get[$name];
    }

    /**
     * @return string
     * @throws BadRequest
     */
    public function getUri(): string
    {
        return $this->getGetValue('path');// from nginx or REQUEST_URI from server
    }

    /**
     * @param string $name
     * @return mixed
     * @throws BadRequest
     */
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

    /**
     * @return string
     * @throws BadRequest
     */
    public function getMethod(): string
    {
        return $this->getServerValue('REQUEST_METHOD');
    }

    /**
     * @param string $name
     * @return string
     * @throws BadRequest
     */
    public function getHeader(string $name)
    {
        return $this->getServerValue('HTTP_' . $name);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
