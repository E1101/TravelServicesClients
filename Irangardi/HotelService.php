<?php
namespace Tsp\Irangardi;

use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Request\Method;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iDataSetConveyor;
use Poirot\Core\Interfaces\iOptionsProvider;
use Tsp\Irangardi\HotelService\SoapPlatform;
use Tsp\Irangardi\Interfaces\iIRHotel;
use Tsp\Mystifly\ApiClient\SoapTransporter;

class HotelService extends AbstractClient
    implements iOptionsProvider
    , iIRHotel
{
    /** @var HotelServiceOpts */
    protected $options;

    /**
     * HotelService constructor.
     * @param iDataSetConveyor|array $options
     */
    function __construct($options = null)
    {
        if ($options != null)
            $this->inOptions()->from($options);
    }

    // Client API:

    /**
     * To receive the list of cities/code that exist in our system.
     *
     * @return mixed
     */
    function getCityList()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    protected function newMethod($methodName, array $args = null)
    {
        $method = new Method;

        $method->setMethod($methodName);

        ## account data options
        ## these arguments is mandatory on each call
        $method->setArguments([
            'OprCod'  => $this->inOptions()->getOprCod(),
            'CustCod' => $this->inOptions()->getCustCod(),
            'PID'     => $this->inOptions()->getPID(),
            'Mojavez' => $this->inOptions()->getMojavez(),
        ]);

        return $method;
    }

    // Client Implementation:

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    function platform()
    {
        if (!$this->platform)
            $this->platform = new SoapPlatform($this);

        return $this->platform;
    }

    /**
     * Get Transporter Adapter
     *
     * @return iConnection
     */
    function transporter()
    {
        if (!$this->transporter)
            // with options build transporter
            $this->transporter = new SoapTransporter(array_merge(
                $this->inOptions()->getConnConfig()
                , ['server_url' => $this->inOptions()->getServerUrl()]
            ));

        return $this->transporter;
    }


    // ...

    /**
     * @return HotelServiceOpts
     */
    function inOptions()
    {
        if (!$this->options)
            $this->options = self::newOptions();

        return $this->options;
    }

    /**
     * @inheritdoc
     *
     * @param null|mixed $builder Builder Options as Constructor
     *
     * @return HotelServiceOpts
     */
    static function newOptions($builder = null)
    {
        return new HotelServiceOpts;
    }
}
