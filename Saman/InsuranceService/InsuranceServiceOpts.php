<?php
namespace Tsp\Saman\InsuranceService;

use Poirot\Core\AbstractOptions;

class InsuranceServiceOpts extends AbstractOptions
{
    protected $serverUrl = 'http://samanservice.ir/TravisService.asmx?WSDL';

    ## account
    protected $username = 'ws@mpT';
    protected $password = 'w@p2016';

    // Transporter Specific Options
    ## soap context options
    protected $connConfig = [
        'connection'=>'close',
    ];


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
    public function getServerUrl()
    {
        return $this->serverUrl;
    }


    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $connConfig
     * @return $this
     */
    public function setConnConfig($connConfig)
    {
        $this->connConfig = $connConfig;
        return $this;
    }

    /**
     * @return array
     */
    public function getConnConfig()
    {
        return $this->connConfig;
    }
}
