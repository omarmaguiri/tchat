<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 13:59
 */

namespace Core;


use Core\Container\Container;
use Core\Container\ContainerInterface;
use Core\Http\RequestInterface;
use Core\Http\Response;
use Core\Router\RouterInterface;
use Core\Security\SecurityInterface;

class Kernel
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var SecurityInterface
     */
    protected $security;


    /**
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container = null)
    {
        $this->initializeContainer($container);
        $this->initializeRouter($this->container->get('router'));
        $this->initializeSecurity($this->container->get('security'));
        $this->container->set('kernel', $this);
    }

    /**
     * return root directory path
     *
     * @return string
     */
    public function getRootDir()
    {
        return PUBLIC_DIRECTORY . '/../';
    }
    /**
     * return config directory path
     *
     * @return string
     */
    public function getConfigDir()
    {
        return $this->getRootDir() . 'config/';
    }
    /**
     * return src directory path
     *
     * @return string
     */
    public function getSrcDir()
    {
        return $this->getRootDir() . 'src/';
    }

    /**
     * Initializes The Service Container.
     *
     * @param ContainerInterface $container
     * @return Container|ContainerInterface
     */
    protected function initializeContainer(ContainerInterface $container = null)
    {
        $configDir = $this->getConfigDir();
        $config = [];
        if (file_exists($configDir . 'services.php')) {
            $config = array_merge($config, require_once $configDir . '/services.php');
        }
        if (file_exists($configDir . 'parameters.php')) {
            $config = array_merge($config, require_once $configDir . '/parameters.php');
        }
        $this->container = new Container($config);
        if ($container) {
            $container->setConfig($config);
            $this->container = $container;
        }
    }

    /**
     * Initializes The Router.
     *
     * @param RouterInterface $router
     */
    protected function initializeRouter(RouterInterface $router)
    {
        $this->router = $router;
        $configDir = $this->getConfigDir();
        $routes = [];
        if (file_exists($configDir . 'routes.php')) {
            $routes = array_merge($routes, require_once $configDir . '/routes.php');
        }
        foreach ($routes as $name => $route) {
            if (!array_key_exists('path', $route) || !array_key_exists('controller', $route)) {
                continue;
            }
            $method = array_key_exists('method', $route) ? strtoupper($route['method']) : RouterInterface::METHOD_ANY;
            $this->router->addRoute($name, $route['path'], $route['controller'], $method);
        }
    }


    /**
     * Initializes The Security.
     * @param SecurityInterface $security
     */
    protected function initializeSecurity(SecurityInterface $security)
    {
        $this->security = $security;
        $configDir = $this->getConfigDir();
        $securityConfig = [];
        if (file_exists($configDir . 'security.php')) {
            $securityConfig = array_merge($securityConfig, require_once $configDir . '/security.php');
        }
        if (array_key_exists('access_control', $securityConfig)) {
            foreach ($securityConfig['access_control'] as $rule) {
                if (!array_key_exists('path', $rule) || !array_key_exists('roles', $rule)) {
                    continue;
                }
                $this->security->addAccessControl($rule['path'], $rule['roles']);
            }
        }
        if (array_key_exists('login', $securityConfig)) {
            $this->security->setLoginPath($securityConfig['login']);
        }
    }

    /**
     * Execute the request
     *
     * @param RequestInterface $request
     * @return string
     * @throws \Exception
     */
    public function handle(RequestInterface $request)
    {
        $this->container->get('session')->start();
        $route = $this->router->getRoute($request->getPath(), $request->getMethod());
        if (!$route) {
            return new Response(Response::$statusTexts[404] . " {$request->getMethod()} {$request->getPath()}", 404);
        }
        if (!$this->security->hasAccess($request->getPath())) {
            if ($this->security->getLoginPath()) {
                $response = new Response();
                $response->setStatus(307);
                $response->setHeader('Location', $this->security->getLoginPath());
                return $response;
            }
            return new Response(Response::$statusTexts[401], 401);
        }
        $response = $this->router->run($route, $this->container);
        if (!$response instanceof Response) {
            throw new \Exception('Expects A Response Object, ' . gettype($response) . ' Given');
        }
        return $response;
    }
    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

}