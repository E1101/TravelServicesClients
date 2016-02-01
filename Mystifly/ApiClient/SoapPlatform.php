<?php
namespace Tsp\Mystifly\ApiClient;

use Poirot\ApiClient\Interfaces\iTransporter;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;

class SoapPlatform implements iPlatform
{
    use SoapRequestTrait;
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
        $output = [
            'status'=>false,
            'data'=>[],
            'Errors'=>[]

        ];
        $output [ 'data' ] = $this->objectToArray($response->{key($response)});
        // update status code
        $this->updateStatus(key($response),$output);
        // update errors array
        $this->updateErrors(key($response),$output);

        return $response->{key($response)};
    }

    function updateStatus($method ,&$data){
        switch($method){
            case 'CreateSessionResult':
                $data [ 'status' ] = $data [ 'data' ][ 'SessionStatus' ];
                unset($data [ 'data' ][ 'SessionStatus' ]);
                unset($data [ 'data' ][ 'Target' ]);
                break;
            case '':
                break;
            case '':
                break;
            default:
                break;
        }
        return ;
    }

    function updateErrors($method ,&$data){
        switch($method){
            case 'CreateSessionResult':
                $data [ 'Errors' ] = $data [ 'data' ][ 'Errors' ];
                unset($data [ 'data' ][ 'Errors' ]);
                break;
            case '':
                break;
            case '':
                break;
            default:
                break;
        }
        return ;
    }

}