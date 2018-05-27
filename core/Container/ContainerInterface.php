<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 14:35
 */

namespace Core\Container;


interface ContainerInterface
{
    public function get($key);
    public function set($key, $value);
    public function setConfig(array $config);
}