<?php
namespace Core\Routing;

class Router
{
    /**
     * @var \Core\Routing\Route[]
     */
    public $routes;
    /**
     * @var string
     */
    protected $domain;
    /**
     * Available request method
     * @var array
     */
    protected $verbs = ['GET', 'POST', 'PUT', 'DELETE'];
    /**
     * Router constructor.
     * @param $domain
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Return all routes
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Find routes by request method and url
     * @param string $method
     * @param $url
     * @return \Core\Routing\Route|void
     */
    public function findRoute(string $method, $url)
    {
        $url = urldecode($url);

        $routes = $this->getRoutes();

        if(!empty($routes))
            foreach ($routes as $route) {
                if(in_array($method,$route->methods) && preg_match($route->urlPattern, $url)){
                    $route->bind($url);
                    return $route;
                }
            }

        return die('route not found');
    }

    /**
     * Create route and add them to array routes
     * @param array $methods
     * @param string $url
     * @param $action
     * @return Route
     */
    protected function add(array $methods, string $url, $action): Route
    {
        return $this->routes[] = (new Route($this->domain))
                            ->setMethods($methods)
                            ->setAction($action)
                            ->setURL($url);
    }

    /**
     * Add route with GET request method
     * @param string $url
     * @param $action
     * @return Route
     */
    public function get(string $url, $action): Route
    {
        return $this->add(['GET'], $url, $action);
    }

    /**
     * Add route with POST request method
     * @param string $url
     * @param $action
     * @return Route
     */
    public function post(string $url, $action): Route
    {
        return $this->add(['POST'], $url, $action);
    }

    /**
     * Add route with PUT request method
     * @param string $url
     * @param $action
     * @return Route
     */
    public function put(string $url, $action): Route
    {
        return $this->add(['PUT'], $url, $action);
    }

    /**
     * route with DELETE request method
     * @param string $url
     * @param $action
     * @return Route
     */
    public function delete(string $url, $action): Route
    {
        return $this->add(['DELETE'], $url, $action);
    }

    /**
     * Add route with selected request method
     * @param $methods
     * @param string $url
     * @param $action
     * @return Route
     */
    public function match($methods, string $url, $action): Route
    {
        $this->checkMethod($methods);
        return $this->add($methods, $url, $action);
    }

    /**
     * Add route with any request methods
     * @param string $url
     * @param $action
     * @return Route
     */
    public function any(string $url, $action): Route
    {
        return $this->add($this->verbs, $url, $action);
    }

    /**
     * Check request method in added route
     * @param $methods
     */
    protected function checkMethod($methods)
    {
        foreach ($methods as $method){
            if(!in_array($method , $this->verbs))
                die('wrong method');
        }
    }

    /**
     * Find right route and call callback function
     * @param $methods
     * @param $url
     * @param $request
     * @param $response
     */
    public function run($methods, $url, $request, $response)
    {
        $this->findRoute($methods, $url)->run($request, $response);
    }

}
