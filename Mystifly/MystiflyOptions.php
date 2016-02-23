<?php
namespace Tsp\Mystifly;

use Poirot\Std\Struct\AbstractOptionsData;
use Tsp\Mystifly\ApiClient\FileStorage;
use Tsp\Mystifly\Interfaces\iStorage;

class MystiflyOptionsData extends AbstractOptionsData
{
    ### http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl
    ### http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl
//    protected $serverUrl = 'http://apidemo.myfarebox.com/V2/OnePoint.svc?singlewsdl';
    protected $serverUrl = 'http://webservices.myfarebox.com/V2/OnePoint.svc?singleWsdl';

    // General Api Service Options
//    protected $accountNumber = 'MCN004100';
//    protected $userName      = 'MARCOXML';
//    protected $password      = 'MP2014_xml';
//    protected $target        = 'Test';
    protected $accountNumber = 'MCN008500';
    protected $userName      = 'MARCOPOLOXML';
    protected $password      = 'MARCOPOLO2015_xml';
    protected $target        = 'Production';

    // Transporter Specific Options
    protected $connectionConfig = [
        'connection'=>'close',
    ];

    /** @var iStorage */
    protected $storage;


    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @param string $serverUrl
     * @return $this
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;
        return $this;
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
                // generate proper data for setter
                $data = empty($key)? $arguments[0] : [  $key => $arguments[0] ] ;
                //update Storage support
                $updateStorage = isset($arguments[1]) ? $arguments[1] : false ;

                $this->storage()->set($data, $updateStorage);
                break;

            case 'get':
                /*
                 * supports :
                 * [0] => get()
                 * [1] => get('key1')
                 * [2] => get('key1','key2')
                 * [3] => getKey1()
                 * [4] => getKey2('returnDefault')
                **/

                // generate proper data for getter , empty key means get() called
                $data = empty($key) ? (isset($arguments[0]) ? $arguments : null )  : [ $key ] ;

                // if key is empty then there is no default value
                $default = empty($key) ? null : (isset($arguments[0]) && count($arguments[0])==1 ? $arguments[0] : null ) ;

                return $this->storage()->get($data, $default);
                break;

            default:
                $this->storage()->{$name}();
                break;
        }

        return $this;
    }
}
