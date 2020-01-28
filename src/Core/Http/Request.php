<?php

namespace Core\Http;

class Request extends Message
{
    protected $files;
    protected $cookies;
    protected $query;
    protected $form;

    public function __construct()
    {
        $this->setHeaders(getallheaders());
        $this->setHttpVersion( $_SERVER['SERVER_PROTOCOL']);
        $this->setRequestMethod($_SERVER['REQUEST_METHOD']);
        $this->setRequestUrl($_SERVER['REQUEST_URI']);
        $this->setRequestPort($_SERVER['SERVER_PORT']);
        $this->setDomain($_SERVER['SERVER_NAME']);
        $this->setScheme($_SERVER['Https'] ? 'https' : 'http');
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->form = $_POST;
        $this->query = $_GET;
    }

    /**
     * @return mixed
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->checkHeader("X-Requested-With", "XMLHttpRequest");
    }

}
