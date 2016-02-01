<?php
namespace Tsp\Mystifly;

use Poirot\Core\AbstractOptions;

class MystiflyOptions extends AbstractOptions
{
    // General Api Service Options
    protected $accountNumber = 'MCN004100';
    protected $userName      = 'MARCOXML';
    protected $password      = 'MP2014_xml';
    protected $target        = 'Test';
    // Connection Specific Options


    // 'http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl';
    protected $configs = [
        'wsdlLink' => 'http://apidemo.myfarebox.com/V2/OnePoint.svc?singlewsdl',
        'connection'=>'close',
    ];


    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     * @return $this
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
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
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
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
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
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
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param mixed $configs
     * @return $this
     */
    public function setConfigs($configs)
    {
        $this->configs = $configs;
        return $this;
    }
}
