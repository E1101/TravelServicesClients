<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\Connection\AbstractConnection;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Core\AbstractOptions;
use Poirot\Stream\Streamable;

class SoapTransporter extends AbstractConnection
    implements iConnection
{
    /** @var \SoapClient */
    protected $transporter;

    protected $result;

    /**
     * Get Prepared Resource Transporter
     *
     * - prepare resource with options
     *
     * @throws ConnectException
     * @return mixed Transporter Resource
     */
    function getConnect()
    {
        if ($this->isConnected())
            $this->close();

        $wsdlLink    = $this->inOptions()->getServerUrl();
        $soapConfigs = $this->inOptions()->toArray();

        return $this->transporter = new \SoapClient($wsdlLink, $soapConfigs);
    }

    /**
     * Send Expression On the wire
     *
     * !! get expression from getRequest()
     *
     * @throws ApiCallException|ConnectException
     * @return mixed Response
     */
    function doSend()
    {
        $expr = $this->getRequest();

        // call specific endpoint
        $methodName = key($expr);

        return $this->result = $this->getConnect()->{$methodName}($expr[$methodName]);
    }

    /**
     * Receive Server Response
     *
     * - it will executed after a request call to server
     *   by send expression
     * - return null if request not sent
     *
     * @throws \Exception No Transporter established
     * @return null|string|Streamable
     */
    function receive()
    {
        return $this->result;
    }

    /**
     * Is Transporter Resource Available?
     *
     * @return bool
     */
    function isConnected()
    {
        if(is_null($this->transporter))
            return false;

        return true;
    }

    /**
     * Close Transporter
     * @return void
     */
    function close()
    {
        $this->transporter = null;
    }
}