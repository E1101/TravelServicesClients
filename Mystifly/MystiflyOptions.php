<?php
namespace Tsp\Mystifly;

use Poirot\Core\AbstractOptions;
use Tsp\Mystifly\ApiClient\FileStorage;
use Tsp\Mystifly\Interfaces\iStorage;

class MystiflyOptions extends AbstractOptions
{
    // General Api Service Options
    protected $accountNumber = 'MCN004100';
    protected $userName      = 'MARCOXML';
    protected $password      = 'MP2014_xml';
    protected $target        = 'Test';

    // Transporter Specific Options
    // 'http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl';
    protected $connectionConfig = [
        'wsdlLink' => 'http://apidemo.myfarebox.com/V2/OnePoint.svc?singlewsdl',
        'connection'=>'close',
    ];

    /** @var iStorage */
    protected $storage;

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
    public function getConnectionConfig()
    {
        return $this->connectionConfig;
    }

    /**
     * @param mixed $connectionConfig
     * @return $this
     */
    public function setConnectionConfig($connectionConfig)
    {
        $this->connectionConfig = $connectionConfig;
        return $this;
    }


    // storage:

    /**
     * Set Data Storage
     * @param iStorage $storage
     * @return $this
     */
    public function setStorage(iStorage $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    function storage()
    {
        if (!$this->storage)
            $this->storage = new FileStorage;

        return $this->storage;
    }

    // ...

    function __call($name, $arguments)
    {
        ## get[PropertyName]
        $key = substr($name, 3);

        ## [set]PropertyName
        switch (substr($name, 0, 3)) {
            case 'set':
                $this->storage()->set([$key => $arguments[0]], false);
                break;
            case 'get':
                return $this->storage()->get($key);
                break;
        }

        return $this;
    }
}
