<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Response;
use Poirot\Connection\Interfaces\iConnection;
use Tsp\Mystifly\Util;

class SoapPlatform implements iPlatform
{
    use SoapPlatformTrait;

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
        // TODO: Implement prepareTransporter() method.
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
        $expressionMaker = 'makeRequest'.ucfirst($method->getMethod());

        // generate proper expression base on transporter
        return $this->{$expressionMaker}($method->getArguments());
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

        /** @var iResponse $response */
        $response = $this->exceptionHandler($response);

        if(is_a($response->hasException() ,'\Tsp\Mystifly\Exception\InvalidSessionException')){
            //die('Session Expired');
        }

        return $response;
    }
}