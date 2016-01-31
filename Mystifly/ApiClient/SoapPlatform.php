<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Interfaces\iTransporter;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;

class SoapPlatform implements iPlatform
{
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
        $expressionMaker = 'make'.ucfirst($method->getMethod());

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
        // TODO: Implement makeResponse() method.
        // TODO: Error Handling goes here.
        print_r($response);
        die('make Response');
    }

    protected function makeCreateSession($arguments)
    {
        return ["CreateSession" => [
            'rq' => [
                    "AccountNumber" => $arguments['account_number'],
                    "UserName"      => $arguments['user_name'],
                    "Password"      => $arguments['password'],
                    "Target"        => $arguments['target'],
                ]
            ]
        ];
    }

}