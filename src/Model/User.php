<?php
/**
 * User: Omar
 * Date: 26/05/2018
 * Time: 17:41
 */

namespace App\Model;

use Core\Security\BaseUser;

class User extends BaseUser
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var integer
     */
    protected $gender;
    /**
     * @var integer
     */
    protected $active;
    /**
     * @var string
     */
    protected $avatar_color;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return self
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param int $active
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarColor()
    {
        return $this->avatar_color;
    }

    /**
     * @param string $avatar_color
     * @return $this
     */
    public function setAvatarColor($avatar_color)
    {
        $this->avatar_color = $avatar_color;
        return $this;
    }

}