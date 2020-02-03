<?php

namespace Core;

use Core\Database\Database;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;

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
     * App constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request->getDomain());
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
     * @param string $host
     * @param string $databaseName
     * @param string $user
     * @param string $password
     * @return $this
     */
    public function setDatabase(string $host, string $databaseName, string $user, string $password)
    {
        $this->database = new Database($host, $databaseName, $user, $password);
        return $this;
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