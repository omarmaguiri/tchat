<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 13:41
 */

namespace Core\Http;


class Request implements RequestInterface
{

    /**
     * Gets query param
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
    }
    /**
     * Gets post param
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getPostParam($key, $default = null)
    {
        return array_key_exists($key, $_POST) ? $_POST[$key] : $default;
    }
    /**
     * Gets HTTP Request Method
     *
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param $method
     *
     * @return bool
     */
    public function isMethod($method)
    {
        return strtoupper($method) === $this->getMethod();
    }
    /**
     * Gets requested path
     *
     * @return string
     * @throws \Exception
     */
    public function getPath()
    {
        if (array_key_exists('PATH_INFO', $_SERVER)) {
            return '/' . trim($_SERVER['PATH_INFO'], '/');
        }
        return '/';
    }
    /**
     * Gets requested uri
     *
     * @return string
     */
    public function getUri()
    {
        return trim($_SERVER['REQUEST_URI']);
    }
    /**
     * Gets post raw
     *
     * @return string
     */
    public function getPostRaw()
    {
        return file_get_contents('php://input');
    }

}