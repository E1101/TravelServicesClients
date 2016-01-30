<?php
namespace Tsp\Mystifly\ApiClient;


use Poirot\ApiClient\AbstractConnection;
use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\ApiClient\Interfaces\iConnection;
use Poirot\Core\AbstractOptions;
use Poirot\Stream\Streamable;

class SoapConnection extends AbstractConnection
    implements iConnection
{
    /** @var \SoapClient */
    protected $connection;

    protected $result;

    /**
     * Get Prepared Resource Connection
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

        $this->connection = new \SoapClient($wsdlLink,$soapConfigs);

    }

    /**
     * Send Expression To Server
     *
     * - send expression to server through connection
     *   resource
     * - get connect if connection not stablished yet
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
        return $this->result = $this->connection->{$methodName}($expr[$methodName]);
    }

    /**
     * Receive Server Response
     *
     * - it will executed after a request call to server
     *   by send expression
     * - return null if request not sent
     *
     * @throws \Exception No Connection established
     * @return null|string|Streamable
     */
    function receive()
    {
        return $this->result;
    }

    /**
     * Is Connection Resource Available?
     *
     * @return bool
     */
    function isConnected()
    {
        if(is_null($this->connection))
            return false;
        return true;
    }

    /**
     * Close Connection
     * @return void
     */
    function close()
    {
        // TODO: Implement close() method.
    }
}