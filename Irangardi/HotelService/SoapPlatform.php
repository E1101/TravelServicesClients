<?php
namespace Tsp\Irangardi\HotelService;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Response;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Core\Interfaces\iOptionsProvider;
use Tsp\Irangardi\HotelService;
use Tsp\Mystifly\Util;

class SoapPlatform implements iPlatform
{
    /** @var HotelService */
    protected $client;

    /**
     * SoapPlatform constructor.
     * @param HotelService $client
     */
    function __construct($client)
    {
        $this->client = $client;
    }

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
        if ($transporter instanceof iOptionsProvider) {
            ## reconnect if options changed
            if ($transporter->inOptions()->toArray() !== $connConfig = $this->client->inOptions()->getConnConfig()) {
                $transporter->inOptions()->from($connConfig);
                $transporter->getConnect(); ## reconnect with new options
            }
        }

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
        $result = current(Util::toArray($response));

        $response  = new Response([
            'raw_body' => $result,

            ## get response message as array
            'default_expected' => function($rawBody) use ($result) {
                return $result;
            }
        ]);

        // TODO handle exceptions

        return $response;
    }
}