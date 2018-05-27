<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 15:10
 */

namespace Core\Http;

class Response
{

    protected $version;
    protected $status;
    protected $headers = [];
    protected $content;
    protected $cookies = [];
    protected $statusText;

    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    );

    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setHeaders($headers);
        $this->setStatus($status);
        $this->setProtocolVersion('1.0');
        $this->cookies = [];
    }
    public function getProtocolVersion()
    {
        return $this->version;
    }
    public function setProtocolVersion($version)
    {
        $this->version = $version;
        return $this;
    }
    public function getHeaders()
    {
        return $this->headers;
    }
    public function setHeaders(array $headers = [])
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
        return $this;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($code, $text = null)
    {
        $this->status = (int)$code;
        if ($this->status < 100 || $this->status >= 600) {
            throw new \InvalidArgumentException('The HTTP status code ' . $code . ' is not valid.');
        }
        if (!$text) {
            $this->statusText = array_key_exists($code, self::$statusTexts) ? self::$statusTexts[$code] : '';
            return $this;
        }
        $this->statusText = $text;
        return $this;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        if ($content && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException('The Response content must be a string or object implementing __toString(), ' . gettype($content) . ' given.');
        }
        $this->content = (string)$content;
        return $this;
    }
    public function getVersion()
    {
        return $this->version;
    }
    public function setVersion($version)
    {
        $this->version = $version;
    }
    public function getCookies()
    {
        return $this->cookies;
    }
    public function setCookies(array $cookies)
    {
        foreach ($cookies as $cookie){
            $this->setCookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
        }
        return $this;
    }

    public function setHeader($name, $value = null)
    {
        // Check if $name needs to be split
        if ($value === null && (strpos($name, ':') > 0)) {
            @list($name, $value) = explode(':', $name, 2);
        }
        // Make sure the name is valid
        if (!preg_match('/^[a-zA-Z0-9-]+$/', $name)) {
            throw new \InvalidArgumentException("{$name} is not a valid HTTP header name");
        }
        $key = $normalized_name = strtolower($name);
        //Normaliation of the HTTP header
        $normalized_name = array_map('ucfirst', array_map('trim', explode('-', $normalized_name)));
        $normalized_name = array_key_exists(1, $normalized_name) ? implode('-', $normalized_name) : implode('', $normalized_name);
        // If $value is null or false, unset the header
        if (!$value) {
            unset($this->headers[$key]);
            return $this;
        }
        // Header names are stored lowercase internally.
        $this->headers[$key] = ['name' => $normalized_name, 'value' => trim($value)];
        return $this;
    }
    public function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true)
    {
        // from PHP source code
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException('The cookie name "' . $name . '" contains invalid characters.');
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->format('U');
        } else if (!is_numeric($expire)) {
            $expire = strtotime($expire);
            if (-1 === $expire) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }

        $this->cookies[$name]['name'] = $name;
        $this->cookies[$name]['value'] = $value;
        $this->cookies[$name]['expire'] = $expire;
        $this->cookies[$name]['path'] = $path;
        $this->cookies[$name]['domain'] = $domain;
        $this->cookies[$name]['secure'] = (bool) $secure;
        $this->cookies[$name]['httpOnly'] = (bool) $httpOnly;

        return $this;
    }

    public function sendHeaders()
    {
        if (headers_sent()){
            return $this;
        }

        foreach ($this->getHeaders() as $header){
            header($header['name'].': '.$header['value'], false, $this->status);
        }

        //send Cookies
        foreach ($this->getCookies() as $cookie){

            $name =$cookie['name'];
            $val = $cookie['value'];
            $expiresTime = $cookie['expire'];
            $path = $cookie['path'];
            $domain = $cookie['domain'];
            $secure = (bool) $cookie['secure'];
            $httpOnly = (bool) $cookie['httpOnly'];

//            \setcookie($name, $val, $expiresTime, $path , $domain, $secure, $httpOnly);
            \setcookie($name, $val, $expiresTime);
        }
        return $this;
    }
    public function sendContent()
    {
        echo $this->content;
        return $this;
    }
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    public function __toString()
    {
        return
            sprintf('HTTP/%s %s %s', $this->version, $this->status, $this->statusText)."\r\n".
            $this->headers__toString().
            $this->getContent();
    }
}