<?php
namespace Tsp\Travellanda\Reservation;

use Poirot\ApiClient\AbstractTransporter;
use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\Stream\Streamable;

class FakerTransporter extends AbstractTransporter
{
    protected $connected;

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
        $fakerData = __DIR__.'.faker-data.php';
        if (!file_exists($fakerData))
            throw new ConnectException;

        return $this->connected = include_once $fakerData;
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
        kd($expr);
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
        // TODO: Implement receive() method.
    }

    /**
     * Is Transporter Resource Available?
     *
     * @return bool
     */
    function isConnected()
    {
        return ($this->connected !== null);
    }

    /**
     * Close Transporter
     * @return void
     */
    function close()
    {
        $this->connected = null;
    }
}