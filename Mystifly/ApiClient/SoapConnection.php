<?php
namespace Tsp\Mystifly\ApiClient;


use Poirot\ApiClient\AbstractTransporter;
use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\ApiClient\Interfaces\iTransporter;
use Poirot\Core\AbstractOptions;
use Poirot\Stream\Streamable;

class SoapTransporter extends AbstractTransporter
    implements iTransporter
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
     * @return void
     */
    function getConnect()
    {
        $soapConfigs = $this->inOptions()->toArray();

        $wsdlLink = $soapConfigs['wsdlLink'];
        unset($soapConfigs['wsdlLink']);

        $this->transporter = new \SoapClient($wsdlLink,$soapConfigs);

    }

    /**
     * Send Expression To Server
     *
     * - send expression to server through transporter
     *   resource
     * - get connect if transporter not stablished yet
     *
     * @param mixed $expr Expression
     *
     * @throws ApiCallException
     * @return mixed Prepared Server Response
     */
    function send($expr)
    {
        // check if connector not initialized
        if( ! $this->isConnected() )
            $this->getConnect();

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
        // TODO: Implement close() method.
    }
}