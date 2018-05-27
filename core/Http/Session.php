<?php

namespace Core\Http;


class Session implements SessionInterface
{

    public function start()
    {
        return session_start();
    }

    public function destroy()
    {
        return session_destroy();
    }

    public function has($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    public function get($name, $default = null)
    {
        return $this->has($name) ? $_SESSION[$name] : $default;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function all()
    {
        return $_SESSION;
    }

    public function remove($name)
    {
        if($this->has($name)) {
            unset($_SESSION[$name]);
        }
    }

    public function clear()
    {
        unset($_SESSION);
    }

    public function hasBeenStarted()
    {
        return session_id() !== '';
    }

}
