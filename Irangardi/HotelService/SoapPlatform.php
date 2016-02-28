<?php
namespace Tsp\Irangardi\HotelService;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Response;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Std\Interfaces\ipOptionsProvider;
use Tsp\Irangardi\Exception\NoRoomAvailableException;
use Tsp\Irangardi\HotelService;
use Tsp\Mystifly\Util;

class SoapPlatform implements iPlatform
{
    /** @var HotelService */
    protected $client;

    protected $_lastMethod;

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
        if ($transporter instanceof ipOptionsProvider) {
            ## reconnect if options changed
            if (\Poirot\Std\iterator_to_array($transporter->optsData()) !== $connConfig = $this->client->optsData()->getConnConfig()) {
                $transporter->optsData()->from($connConfig);
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
        $this->_lastMethod = $method;
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

        // handle exceptions
        $method = ucfirst($this->_lastMethod->getMethod());
        $method = '_validate'.$method;
        if (method_exists($this, $method))
            call_user_func([$this, $method], $response);

        return $response;
    }

    // ...

    /** @param iResponse $response */
    protected function _validateForoshRoomFromTemporary($response)
    {
        $result = $response->expected();
        switch ($result) {
            case -1:
                $exception = new \Exception('Temporary Reservation Is Canceled.', $result);
                break;
            case $result <= 0:
                $exception = new \Exception('Unknown Error!!', $result);
                break;
        }
        if (isset($exception))
            $response->setException($exception);
    }

    /** @param iResponse $response */
    protected function _validateReserveRoom($response)
    {
        $result = $response->expected();
        if (!is_int($result))
            ## nothing to do
            return;

        switch ($result) {
            case -10: $exception = new \Exception('Invalid User Info.', $result);
                      break;
            case -20: $exception = new \Exception('Invalid Inputs, ResLong and NumRoom must be between 1 and 10 ', $result);
                      break;
            case -9:  $exception = new NoRoomAvailableException($this->_lastMethod, 'No Capacity For Room', $result);
                      break;
            case -1: $exception = new \Exception('Error Input Data', $result);
                      break;
            case -100:
                $exception = new \Exception('Unknown Error', $result);
                break;
        }
        if (isset($exception))
            $response->setException($exception);
    }

    /** @param iResponse $response */
    protected function _validateInsertNamReserveRoomTemporary($response)
    {
        $result = $response->expected();
        if (!is_int($result))
            ## nothing to do
            return;

        if ($result <= 0)
            $exception = new \Exception('Unknown Error', $result);

        if (isset($exception))
            $response->setException($exception);
    }
}