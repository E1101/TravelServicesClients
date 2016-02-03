<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Interfaces\iTransporter;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Response;
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
     * @param iTransporter $transporter
     * @param iApiMethod|null $method
     *
     * @throws \Exception
     * @return iTransporter
     */
    function prepareTransporter(iTransporter $transporter, $method = null)
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
        $response = Util::toArray($response);

        $response  = new Response([
            'raw_body' => $response,

            ## get response message as array
            'default_expected' => function($rawBody) use ($response) {
                return current($response);
            }
        ]);

        // TODO handle exceptions
        /*$errorCode = 15;
        $response->setException(new \Exception(
            'this is error message'
            , $errorCode
        ));*/

        return $response;
    }
}