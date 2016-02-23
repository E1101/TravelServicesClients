<?php
namespace Tsp;

use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\Connection\AbstractConnection;
use Poirot\Connection\Interfaces\iConnection;
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

        $wsdlLink    = $this->optsData()->getServerUrl();

        $soapConfigs = clone $this->optsData();
        $soapConfigs->del('server_url');
        $soapConfigs = \Poirot\Std\iterator_to_array($soapConfigs);
        $transporter = new \SoapClient($wsdlLink, $soapConfigs);

        return $this->transporter = $transporter;
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
        return $this->result = $this->transporter->{$methodName}($expr[$methodName]);
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
