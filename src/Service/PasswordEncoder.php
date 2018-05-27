<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 22:32
 */

namespace App\Service;


class PasswordEncoder
{
    /**
     * Encode a password
     *
     * @param $password
     * @return bool|string
     */
    public function encode($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * verify password with hash
     *
     * @param $password
     * @param $hash
     * @return bool
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}