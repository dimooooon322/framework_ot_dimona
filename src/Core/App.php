<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use \Core\Routing\Router;

class App
{
    /**
     * @var \Core\Routing\Router
     */
    protected $router;
    /**
     * @var \Core\Http\Request
     */
    protected $request;
    /**
     * @var \Core\Http\Response
     */
    protected $response;

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
        $this->router->run($this->request->getRequestMethod(), $this->request->getRequestPath(),$this->request, $this->response);
    }

    /**
     * @param callable $func
     */
    public function setRoutes(callable $func)
    {
        call_user_func($func, $this->router);
    }

    public function pre($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

}