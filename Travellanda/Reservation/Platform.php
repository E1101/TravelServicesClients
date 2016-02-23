<?php
namespace Tsp\Travellanda\Reservation;

use DOMDocument;
use Poirot\ApiClient\Response;
use Poirot\Connection\Http\HttpSocketConnection;
use Poirot\Connection\Http\StreamFilter\DechunkFilter;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Stream\Interfaces\iStreamable;
use Poirot\Stream\Streamable\TemporaryStream;
use Tsp\Travellanda\HotelService;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Tsp\Travellanda\Util;

class Platform implements iPlatform
{
    /** @var HotelService */
    protected $client;

    /**
     * Platform constructor.
     * @param HotelService $client
     */
    function __construct(HotelService $client)
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
     * @param iApiMethod|null  $method
     *
     * @throws \Exception
     * @return iConnection
     */
    function prepareTransporter(iConnection $transporter, $method = null)
    {
        if ($transporter instanceof HttpSocketConnection) {
            $transporter->optsData()->setServerUrl($this->client->optsData()->getServerUrl());
            $transporter->optsData()->setTimeout(30);
            $transporter->optsData()->setPersist(true);
        }

        return $transporter;
    }

    /**
     * Build Platform Specific Expression To Send
     * Trough Transporter
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
                , \Poirot\Std\flatten($method)
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
                    .'<RequestType>'.ucfirst($method->getRequestType()).'</RequestType>'
                .'</Head>'
                .$elementBody
            .'</Request>';

        ## build request object
        $serverUrl  = $this->client->optsData()->getServerUrl();
        $parsSrvUrl = parse_url($serverUrl);

        $path = (isset($parsSrvUrl['path'])) ? ltrim($parsSrvUrl['path'], '/') : '';
        $host = strtolower($parsSrvUrl['host']);
        $body = http_build_query(['xml' => $reqBody], null, '&');
        $request = 'POST /'. $path. $this->__getRequestUriByMethodName($method->getRequestType()). ' HTTP/1.1'."\r\n"
            . 'Host: '.$host."\r\n"
            . 'User-Agent: AranRojan-PHP/'.PHP_VERSION."\r\n"
            ### enable compression if has enabled
            . (($this->client->optsData()->isEnableCompression()) ? 'Accept-Encoding: gzip'."\r\n" : '')
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
     * @param \StdClass $response Server Result {s:header, s:body}
     *
     * @throws \Exception
     * @return iResponse
     */
    function makeResponse($response)
    {
        $parsedHeader = Util::parseResponseHeaders($response->header);

        if ($parsedHeader['status'] !== 200)
            // handle errors
            VOID;

        # filter body content
        /** @var iStreamable $stream */
        $stream = $response->body->rewind();

        if (
            isset($parsedHeader['headers']['Transfer-Encoding'])
            && $parsedHeader['headers']['Transfer-Encoding'] == 'chunked'
        ) {
            // Response Body Contain Compressed Data and Must Decompressed.
            // We are using stream deflate filter
            $stream->getResource()->prependFilter(DechunkFilter::factory(), STREAM_FILTER_READ);
        }

        if (
            isset($parsedHeader['headers']['Content-Encoding'])
            && $parsedHeader['headers']['Content-Encoding'] == 'gzip'
        ) {
            $stream = new TemporaryStream(gzinflate(substr($stream->read(), 10)));
            $stream->rewind();
        }

        # make response:

        $xmlString = $stream->read();
        $parsedRes = $this->xmlstr_to_array($xmlString);

        // TODO handle exceptions

        $response  = new Response([
            'meta'     => Util::parseResponseHeaders($response->header),
            'raw_body' => $xmlString,

            ## get response message as array
            'default_expected' => function($xmlString) use ($parsedRes) {
                return $parsedRes['Body'];
            }
        ]);

        return $response;
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

    // ...

    /**
     * convert xml string to php array - useful to get a serializable value
     *
     * @param string $xmlstr
     * @return array
     * @author Adrien aka Gaarf
     */
    protected function xmlstr_to_array($xmlstr) {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }
    protected function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v) {
                        $output = (string) $v;
                    }
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
}
