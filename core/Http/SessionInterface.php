<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 17:58
 */

namespace Core\Http;


interface SessionInterface
{

    public function start();
    public function destroy();
    public function has($name);
    public function get($name, $default = null);
    public function set($name, $value);
    public function all();
    public function remove($name);
    public function clear();
    public function hasBeenStarted();

}