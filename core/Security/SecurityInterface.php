<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 17:29
 */

namespace Core\Security;


interface SecurityInterface
{
    public function hasAccess($route);
    public function addAccessControl($route, $roles);
    public function setLoginPath($loginPath);
}