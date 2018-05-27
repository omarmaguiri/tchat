<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 14:52
 */

namespace Core\Controller;

use Core\Container\ContainerInterface;
use Core\Http\Response;
use Core\Security\BaseUser;

class Controller
{

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var BaseUser
     */
    protected $user;


    /**
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function get($key)
    {
        return $this->container->get($key);
    }
    public function getUser()
    {
        if (!$this->user) {
            $this->user = unserialize($this->get('session')->get('user'));
        }
        return $this->user;
    }
    public function view($view, array $data = [])
    {
        $view = str_replace(['.', ':' ], '/', $view);
        $view = trim($view, '/');
        $path = $this->container->get('kernel')->getSrcDir() . "views/$view.php";

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('The View "' . $path . '" Was Not Found');
        }
        extract($data);
        ob_start();
        require_once "$path";
        $html = ob_get_clean();
        return new Response($html);
    }
    public function redirect($url, $status = 307)
    {
        if ($status < 300 || $status > 400) {
            throw new \InvalidArgumentException($status . ' is not a valid HTTP Redirection Code');
        }
        $response = new Response();
        $response->setStatus($status);
        $response->setHeader('Location', $url);
        return $response;
    }
    public function json($data, $status = 200, $headers = [])
    {
        $response = new Response(json_encode($data), $status, $headers);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

}