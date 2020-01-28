<?php
namespace Core\Routing;

class Route
{
    /**
     * @var array
     */
    public $methods;
    /**
     * @var array
     */
    public $args = [];
    /**
     * @var array
     */
    public $middleware = [];
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $urlPattern;
    /**
     * @var callable
     */
    public $action;
    /**
     * @var string
     */
    protected $domain;
    /**
     * Route constructor.
     * @param string $domain
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }
    /**
     * Set route's method
     * @param array $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }
    /**
     * Set route's name
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Set route's callback function
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    /**
     * Set route's url
     * @param string $url
     * @return $this
     */
    public function setURL(string $url)
    {
        $this->url = $this->domain . $url;
        $this->setUrlPattern();
        return $this;
    }
    /**
     * Add route's middleware
     * @param $middleware
     * @return $this
     */
    public function addMiddleware($middleware)
    {
        if(is_array($middleware))
            foreach ($middleware as $fn){
                $this->middleware[] = $fn;
            }
        else
            $this->middleware[] = $middleware;
        return $this;
    }
    /**
     * Set pattern for search
     */
    protected function setUrlPattern()
    {
        $pattern = "^";
        if($argsPattern = preg_filter("/\/{([a-zA-Z0-9\-\_]+)\}/",  "/(?P<$1>[a-zA-Z0-9\_\-]+)", $this->url))
            $pattern .= $argsPattern;
        else
            $pattern .= $this->url;
        if(substr($pattern, -1) === '/')
            $pattern .= "?";
        else
            $pattern .= "/?";
        $pattern .= "$";
        $pattern = str_replace('/', '\/', $pattern);
        $this->urlPattern = '/' . $pattern . '/';
    }

    /**
     * Binding args for callback function
     * @param string $url
     */
    public function bind(string $url)
    {
        preg_match_all($this->urlPattern, $url, $args);
        if(count($args) > 1)
        {
            array_shift($args);
            foreach ($args as $arg_key => $arg_value) {
                $this->args[$arg_key] = $arg_value[0];
            };
        }
    }
    /**
     * Call middleware before run main callback
     * @return void
     */
    public function callMiddleware()
    {
        foreach ($this->middleware as $fn){
            call_user_func($fn);
        }
    }

    /**
     * Run callback function
     * @param $request
     * @param $response
     * @return mixed
     */
    public function run($request, $response)
    {
        $this->callMiddleware();
        return call_user_func($this->action, $request, $response, $this->args);
    }
}