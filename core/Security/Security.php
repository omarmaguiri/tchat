<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 17:18
 */

namespace Core\Security;

use Core\Http\SessionInterface;

class Security implements SecurityInterface
{

    /**
     * @var SessionInterface
     */
    protected $session;
    protected $accessControl = [];
    protected $loginPath = '';

    function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function hasAccess($route)
    {
        foreach($this->accessControl as $rule) {
            if(!preg_match($rule['path'], $route)) {
                continue;
            }
            if (in_array('Anonymous', $rule['roles'])) {
                return true;
            }
            $user = unserialize($this->session->get('user'));
            if (!$user) {
                return false;
            }
            $roles = $user->getRoles();
            if (!array_intersect($rule['roles'], $roles)) {
                return false;
            }
            break;
        }
        return true;
    }
    public function addAccessControl($route, $roles)
    {
        $this->accessControl[] = [
            'path' => "#^{$route}\$#",
            'roles' => $roles
        ];
    }

    /**
     * @return string
     */
    public function getLoginPath()
    {
        return $this->loginPath;
    }

    /**
     * @param string $loginPath
     * @return self
     */
    public function setLoginPath($loginPath)
    {
        $this->loginPath = $loginPath;
        return $this;
    }


}