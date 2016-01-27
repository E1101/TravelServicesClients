<?php
namespace Ar\Travellanda\Reservation;

use Poirot\ApiClient\Request\Method;

class ReqMethod extends Method
{
    protected $username;
    protected $password;

    /**
     * Username part of head request
     *
     * @param string $username
     * @return $this
     */
    function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    function getUsername()
    {
        return $this->username;
    }

    /**
     * Password of head request
     * @param string $password
     * @return $this
     */
    function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    function getPassword()
    {
        return $this->password;
    }

    /**
     * Proxy to SetMethod
     * @param string $methodName
     * @return $this
     */
    function setRequestType($methodName)
    {
        $this->setMethod($methodName);
        return $this;
    }

    /**
     * Proxy to getMethod
     */
    function getRequestType()
    {
        return $this->getMethod();
    }
}
