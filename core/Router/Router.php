<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 13:32
 */

namespace Core\Router;


use Core\Container\ContainerInterface;

class Router implements RouterInterface
{

    protected $routes = [];
    protected $regexes= [];

    /**
     * get('/', 'function');
     * @name  get
     * @param string $route
     * @param mixed $callback
     */
    public function get($route, $callback)
    {
        $this->addRoute($route, $callback, static::METHOD_GET);
    }
    /**
     * post('/', 'function');
     * @name  post
     * @param string $route
     * @param mixed $callback
     */
    public function post($route, $callback)
    {
        $this->addRoute($route, $callback, static::METHOD_POST);
    }

    /**
     * Gets The Callable For A Give Path
     * @param $route
     * @param string $method
     * @return array
     * @throws \Exception
     */
    public function getRoute($route, $method = self::METHOD_ANY)
    {
        foreach($this->regexes as $ind => $regex)
        {
            if(preg_match($regex, $route, $arguments))
            {
                array_shift($arguments);
                $def = $this->routes[$ind];
                if($def['httpMethod'] !== self::METHOD_ANY && $method !== $def['httpMethod']) {
                    continue;
                }
                if(
                    (is_array($def['callback']) && method_exists($def['callback'][0], $def['callback'][1]))
                    || function_exists($def['callback'])
                ) {
                    return ['callback' => $def['callback'], 'args' => $arguments];
                }
                throw new \Exception("The Controller Is Not Correctly Defined for route {$regex}");
            }
        }
        return null;
    }

    /**
     * Execute A Given Route
     *
     * @param string $route
     * @param ContainerInterface $container
     * @return Response
     */
    public function run($route, ContainerInterface $container)
    {
        $args = func_get_args();
        array_shift($args);
        if (is_array($route['callback'])) {
            if (count($route['callback']) !== 2) {
                throw new \InvalidArgumentException('Invalid Route Argument');
            }
            list($controller, $action) = $route['callback'];
            if (!class_exists($controller)) {
                throw new \InvalidArgumentException('The Controller ' . $controller . ' Does Not Exist');
            }
            $reflector = new \ReflectionClass($controller);
            if (!$reflector->hasMethod($action)) {
                throw new \InvalidArgumentException('The Action ' . $action . ' Does Not Exist');
            }
            $obj = $reflector->newInstance($container);
            $method = $reflector->getMethod($action);
            return $method->invokeArgs($obj, $route['args']);
        }
        if (is_callable($route['callback'])) {
            $route['args'][] = $container;
            return call_user_func_array($route['callback'], $route['args']);
        }
        throw new \InvalidArgumentException('Invalid Controller');
    }
    /**
     * addRoute('/', 'function', 'GET');
     *
     * @name  addRoute
     * @param string $name
     * @param string $route
     * @param mixed $callback
     * @param mixed $method
     */
    public function addRoute($name, $route, $callback, $method = self::METHOD_ANY)
    {
        $route = '/'.trim($route, '/');
        if (preg_match("/(.*)::(.*)/", $callback, $matches)){
            array_shift($matches);
            $callback = $matches;
        }
        $this->routes[] = ['name' => $name, 'httpMethod' => $method, 'path' => $route, 'callback' => $callback];
        $this->regexes[]= "#^{$route}\$#";
    }

    public function generateUrl($name)
    {

    }

}