<?php
namespace Tsp\Mystifly;

use Poirot\Core\AbstractOptions;

class MystiflyOptions extends AbstractOptions
{
    protected $wsdlLink      = 'http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl';
    protected $accountNumber = 'MCN008500';
    protected $userName      = 'MARCOPOLOXML';
    protected $password      = 'MARCOPOLO2015_xml';
    protected $target        = 'Production';

    /**
     * @return string
     */
    public function getWsdlLink()
    {
        return $this->wsdlLink;
    }

    /**
     * @param string $wsdlLink
     */
    public function setWsdlLink($wsdlLink)
    {
        $this->wsdlLink = $wsdlLink;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }
}
