<?php
namespace Tsp\Irangardi\HotelService;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\Connection\Interfaces\iConnection;

class SoapPlatform implements iPlatform
{
    /**
     * Prepare Transporter To Make Call
     *
     * - validate transporter
     * - manipulate header or something in transporter
     * - get connect to resource
     *
     * @param iConnection $transporter
     * @param iApiMethod|null $method
     *
     * @throws \Exception
     * @return iConnection
     */
    function prepareTransporter(iConnection $transporter, $method = null)
    {
        return $transporter;
    }

    /**
     * Build Platform Specific Expression To Send
     * Trough Transporter
     *
     * @param iApiMethod $method Method Interface
     *
     * @return mixed
     */
    function makeExpression(iApiMethod $method)
    {
        // generate proper expression base on transporter

        ## method name called by soapClient
        $methodName = $method->getMethod();
        return [
            $methodName => $method->getArguments()
        ];
    }

    /**
     * Build Response Object From Server Result
     *
     * - Result must be compatible with platform
     * - Throw exceptions if response has error
     *
     * @param mixed $response Server Result
     *
     * @throws \Exception
     * @return iResponse
     */
    function makeResponse($response)
    {
        kd($response);
    }
}