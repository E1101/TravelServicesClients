<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Interfaces\iConnection;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;

class SoapPlatform implements iPlatform
{
    /**
     * Prepare Connection To Make Call
     *
     * - validate connection
     * - manipulate header or something in connection
     * - get connect to resource
     *
     * @param iConnection $connection
     * @param iApiMethod|null $method
     *
     * @throws \Exception
     * @return iConnection
     */
    function prepareConnection(iConnection $connection, $method = null)
    {
        // TODO: Implement prepareConnection() method.
        return $connection;
    }

    /**
     * Build Platform Specific Expression To Send
     * Trough Connection
     *
     * @param iApiMethod $method Method Interface
     *
     * @return mixed
     */
    function makeExpression(iApiMethod $method)
    {
        $expressionMaker = 'make'.ucfirst($method->getMethod());

        // generate proper expression base on connection
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