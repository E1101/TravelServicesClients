<?php
namespace Tsp\Travellanda\Reservation;

use Poirot\ApiClient\AbstractTransporter;
use Poirot\ApiClient\Exception\ApiCallException;
use Poirot\ApiClient\Exception\ConnectException;
use Poirot\Stream\Streamable;
use Tsp\Travellanda\Util;

class FakerTransporter extends AbstractTransporter
{
    protected $connected;
    protected $response;

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
        $fakerData = __DIR__.'/.faker-data.php';
        if (!file_exists($fakerData))
            throw new ConnectException(sprintf(
                'file (%s) not found.', $fakerData
            ));

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
        if (!$this->isConnected())
            $this->getConnect();


        // look for fake data from request uri:

        $parseReq = Util::parseRequest($expr);

        if (!array_key_exists($parseReq['uri'], $this->connected))
            // method not found
            throw new ApiCallException($parseReq['uri']);

        $response = "HTTP/1.1 200 OK
Server: Travellanda (Faker)
Content-Type: application/xml";

        $response = (object) [
            'header' => $response,
            'body' => new Streamable\TemporaryStream($this->connected[$parseReq['uri']])
        ];

        $this->response = $response;
        return $response;
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
        return $this->response;
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