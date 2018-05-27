<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 15:47
 */

namespace Core\Router;


use Core\Container\ContainerInterface;

interface RouterInterface
{

    const METHOD_ANY = 'ANY';
    const METHOD_GET = 'GET';
    const METHOD_POST= 'POST';

    public function getRoute($route, $httpMethod = self::METHOD_ANY);
    public function addRoute($name, $route, $callback, $method = self::METHOD_ANY);
    public function run($route, ContainerInterface $container);

}