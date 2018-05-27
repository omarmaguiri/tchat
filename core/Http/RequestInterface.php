<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 13:34
 */

namespace Core\Http;


interface RequestInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST= 'POST';

    public function getQueryParam($key, $default = null);
    public function getPostParam($key, $default = null);
    public function getMethod();
    public function isMethod($method);
    public function getPath();
    public function getUri();
    public function getPostRaw();
}