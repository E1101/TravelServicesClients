<?php
namespace Tsp\Travellanda;

use Poirot\Core\AbstractOptions;

class HotelServiceOpts extends AbstractOptions
{
    // TODO build values from config
    protected $serverUrl = 'http://xmldemo.Travellanda.com/xmlv1';
    protected $username  = '3222b42a21a8c237d23b4cf4c02de6c1';
    protected $password  = '61R2CNXF11Qh';

    /** reduces the response size very efficiently with gzip response compression */
    protected $enableCompression = true;
    protected $enableFaker = false;

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
        $this->serverUrl = (string) $serverUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * @return boolean
     */
    public function isEnableCompression()
    {
        return $this->enableCompression;
    }

    /**
     * @param boolean $enableCompression
     * @return $this
     */
    public function setEnableCompression($enableCompression = true)
    {
        $this->enableCompression = (boolean) $enableCompression;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableFaker()
    {
        return $this->enableFaker;
    }

    /**
     * @param boolean $enableFaker
     * @return $this
     */
    public function setEnableFaker($enableFaker)
    {
        $this->enableFaker = (boolean) $enableFaker;
        return $this;
    }
}
