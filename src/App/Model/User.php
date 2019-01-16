<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 13.01.19
 * Time: 23:36
 */

namespace App\Model;


use Core\Model\AbstractModel;
use Symfony\Component\Validator\Constraints as Assert;
use Bukashk0zzz\FilterBundle\Annotation\FilterAnnotation as Filter;
class User extends AbstractModel
{
    protected $user_id;

    /**
     * @Filter("StringTrim")
     * @Filter("StripTags")
     * @Filter("StripNewlines")
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255)
     */
    protected $username;

    /**
     * @Filter("StringTrim")
     * @Filter("StripTags")
     * @Filter("StripNewlines")
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=255)
     */
    protected $bcryptpass;


    /**
     * @Filter("StringTrim")
     * @Filter("StripTags")
     * @Filter("StripNewlines")
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min=6, max=25)
     */
    protected $user_email;

    /**
     * @Filter("StringTrim")
     * @Filter("StripTags")
     * @Filter("StripNewlines")
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=25)
     */
    protected $user_display_name;


    protected $user_active = 1;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return User
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }/**
 * @return mixed
 */
public function getUsername()
{
    return $this->username;
}/**
 * @param mixed $username
 * @return User
 */
public function setUsername($username)
{
    $this->username = $username;
    return $this;
}/**
 * @return mixed
 */
public function getBcryptpass()
{
    return $this->bcryptpass;
}/**
 * @param mixed $bcryptpass
 * @return User
 */
public function setBcryptpass($bcryptpass)
{
    $this->bcryptpass = $bcryptpass;
    return $this;
}


    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->user_email;
    }

    /**
     * @param mixed $user_email
     * @return User
     */
    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserDisplayName()
    {
        return $this->user_display_name;
    }

    /**
     * @param mixed $user_display_name
     * @return User
     */
    public function setUserDisplayName($user_display_name)
    {
        $this->user_display_name = $user_display_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserActive()
    {
        return $this->user_active;
    }

    /**
     * @param mixed $user_active
     * @return User
     */
    public function setUserActive($user_active)
    {
        $this->user_active = $user_active;
        return $this;
    }


}