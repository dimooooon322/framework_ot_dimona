<?php

namespace Core;

use Core\Database\Database;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;
use Core\Support\Collection;

class App
{
    /**
     * @var \Core\Routing\Router
     */
    protected $router = null;
    /**
     * @var \Core\Http\Request
     */
    protected $request = null;
    /**
     * @var \Core\Http\Response
     */
    protected $response = null;
    /**
     * @var \Core\Database\Database
     */
    protected $database = null;
    /**
     * @var Collection
     */
    protected $config = null;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->loadConfig();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request->getDomain());
        $this->database = new Database($this->getConfig("dbHost"),
            $this->getConfig("dbName"),
            $this->getConfig("dbUser"),
            $this->getConfig("dbPassword"));
    }

    /**
     * Run app.
     */
    public function run()
    {
        $this->router->run($this->request->getRequestMethod(), $this->request->getRequestPath(), $this->request, $this->response);
    }

    /**
     * @param callable $func
     * @return $this
     */
    public function setRoutes(callable $func)
    {
        call_user_func($func, $this->router);
        return $this;
    }

    /**
     * Load config from 'config.json'
     */
    protected function loadConfig()
    {
        $file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/config.json');
        $this->config = collect(json_decode($file, true));
    }

    /**
     * Get config value
     * @param $name
     * @return mixed
     */
    public function getConfig($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function pre($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }


}