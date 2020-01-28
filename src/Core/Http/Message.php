<?php

namespace Core\Http;

use function Sodium\compare;

class Message
{
    /**
     * @var string
     */
    protected $httpVersion;
    /**
     * @var string
     */
    protected $scheme;
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var array
     */
    protected $query;
    /**
     * @var array
     */
    protected $body;
    /**
     * @var int
     */
    protected $code;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var string
     */
    protected $requestMethod;
    /**
     * @var string
     */
    protected $requestUrl;
    /**
     * @var int
     */
    protected $requestPort;

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Return header's value or false if header wasn't sent
     * @param string $name
     * @return mixed
     */
    public function getHeader($name)
    {
        if(!$this->hasHeader($name))
            return false;
        return $this->headers[$name];
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return int
     */
    public function getRequestPort()
    {
        return $this->requestPort;
    }

    /**
     * @param string $httpVersion
     * @return $this
     */
    public function setHttpVersion(string $httpVersion)
    {
        $this->httpVersion = $httpVersion;
        return $this;
    }

    /**
     * @param string $requestMethod
     * @return $this
     */
    public function setRequestMethod(string $requestMethod)
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @param int $requestPort
     * @return $this
     */
    public function setRequestPort(int $requestPort)
    {
        $this->requestPort = $requestPort;
        return $this;
    }

    /**
     * @param string $requestUrl
     * @return $this
     */
    public function setRequestUrl(string $requestUrl)
    {
        $this->requestUrl = $requestUrl;
        return $this;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setHeader(string $name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @return string
     */
    public function getRequestPath(): string
    {
        return $this->getDomain() . explode("?", $_SERVER['REQUEST_URI'])[0];
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Checking header's value with needed value.
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function checkHeader(string $name, $value)
    {
        return strtolower($this->getHeader($name)) === strtolower($value);
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function addHeader(string $name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers)
    {
        array_merge($this->getHeaders(), $headers);
        return $this;
    }
}