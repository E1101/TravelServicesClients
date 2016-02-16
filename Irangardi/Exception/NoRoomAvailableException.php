<?php
namespace Tsp\Irangardi\Exception;

use Poirot\ApiClient\Interfaces\Request\iApiMethod;

class NoRoomAvailableException extends \Exception
{
    /** @var iApiMethod */
    protected $method;

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param \Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     * @since 5.1.0
     */
    function __construct(iApiMethod $reqMethod, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->method = $reqMethod;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get Method Call That Rise This Exception
     * @return iApiMethod
     */
    function getCalledMethod()
    {
        return $this->method;
    }
}
