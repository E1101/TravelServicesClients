<?php
namespace Ar\Travellanda\Reservation;

use Ar\Travellanda\Reservation;
use Poirot\ApiClient\Connection\HttpStreamConnection;
use Poirot\ApiClient\Interfaces\iConnection;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;

class Platform implements iPlatform
{
    /** @var Reservation */
    protected $client;

    /**
     * Platform constructor.
     * @param Reservation $client
     */
    function __construct(Reservation $client)
    {
        $this->client = $client;
    }

    /**
     * Prepare Connection To Make Call
     *
     * - validate connection
     * - manipulate header or something in connection
     * - get connect to resource
     *
     * @param iConnection $connection
     * @param iApiMethod|null  $method
     *
     * @throws \Exception
     * @return iConnection
     */
    function prepareConnection(iConnection $connection, $method = null)
    {
        if (!$connection instanceof HttpStreamConnection)
            throw new \InvalidArgumentException(
                'Invalid Connection Provided. it must be instance of HttpStreamConnection'
            );

        $connection->inOptions()->setServerUrl($this->client->inOptions()->getServerUrl());
        $connection->inOptions()->setTimeout(30);
        $connection->inOptions()->setPersist(true);

        return $connection;
    }

    /**
     * Build Platform Specific Expression To Send
     * Trough Connection
     *
     * @param ReqMethod $method Method Interface
     *
     * @return mixed
     */
    function makeExpression(iApiMethod $method)
    {
        if (!$method instanceof ReqMethod)
            throw new \InvalidArgumentException(sprintf(
                'Method must be instance of Reservation\ReqMethod, "%s" given.'
                , \Poirot\Core\flatten($method)
            ));


        # make expression

        $_f__makeArgs = function($arguments) use (&$_f__makeArgs) {
            $bodyArgs = '';
            foreach($arguments as $key => $val)
            {
                if (is_array($val)) {
                    if (array_values($val) !== $val /* is associate array */) {
                        $bodyArgs .= "<{$key}>".$_f__makeArgs($val)."</{$key}>";
                    } else {
                        /*
                         * <HotelIds>
                         *  <HotelId>1009075</HotelId>
                         *  <HotelId>1000740</HotelId>
                         *  .....
                         */
                        foreach($val as $v)
                            $bodyArgs .= "<{$key}>".$v."</{$key}>";
                    }
                } else {
                    $bodyArgs .= "<{$key}>".$val."</{$key}>";
                }
            }

            return $bodyArgs;
        };

        $elementBody = $_f__makeArgs($method->getArguments());
        $elementBody = ($elementBody !== '') ? '<Body>'.$elementBody.'</Body>' : '<Body/>';
        $reqBody     =
            '<Request>'
                .'<Head>'
                    .'<Username>'.$method->getUsername().'</Username>'
                    .'<Password>'.$method->getPassword().'</Password>'
                    .'<RequestType>'.$method->getRequestType().'</RequestType>'
                .'</Head>'
                .$elementBody
            .'</Request>';

        ## build request object
        $serverUrl  = $this->client->inOptions()->getServerUrl();
        $parsSrvUrl = parse_url($serverUrl);

        $path = (isset($parsSrvUrl['path'])) ? ltrim($parsSrvUrl['path'], '/') : '';
        $host = $parsSrvUrl['host'];
        $body = http_build_query(['xml' => $reqBody], null, '&');
        $request = 'POST /'. $path. $this->__getRequestUriByMethodName($method->getRequestType()). ' HTTP/1.1'."\r\n"
            . 'Host: '.$host."\r\n"
            . 'User-Agent: AranRojan-PHP/'.PHP_VERSION."\r\n"
            . 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'."\r\n"
            ### enable compression if has enabled
            . (($this->client->inOptions()->isEnableCompression()) ? 'Accept-Encoding: gzip'."\r\n" : '')
            ### post method need request header with Content-Length
            . 'Content-Length: '.strlen($body)."\r\n"
            . 'Content-Type: application/x-www-form-urlencoded'."\r\n"
            . "\r\n"
            . $body;

        return $request;
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
        /**
         * <Response>
        <Head>
        <ServerTime>2013-12-01T14:00:00</ServerTime>
        <ResponseType>HotelBookingCancel</ResponseType>
        </Head>
        <Body>
        <Error>
        <ErrorId>121</ErrorId>
        <ErrorText>
        Cancellation error. This booking has already been cancelled before.
        </ErrorText>
        </Error>
        </Body>
        </Response>
         */
        // TODO enable compression filter
        k($response);
    }


    // ...

    /**
     * Get specific uri on server for a method call
     * @param string $methodName
     * @return string
     */
    protected function __getRequestUriByMethodName($methodName)
    {
        /*
         'GetCountries' => '/GetCountriesRequest.xsd',
         'GetCities'    => '/GetCitiesRequest.xsd',
         'GetHotels'    => '/GetHotelsRequest.xsd',
         */

        $methodName = ucfirst($methodName);
        $uri       = '/'.$methodName.'Request.xsd';

        return $uri;
    }
}
