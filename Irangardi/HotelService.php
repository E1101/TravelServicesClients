<?php
namespace Tsp\Irangardi;

use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iOptionsProvider;
use tsp\Irangardi\Interfaces\iIRHotel;

class HotelService extends AbstractClient
    implements iOptionsProvider
    , iIRHotel
{
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
        // TODO: Implement platform() method.
    }

    /**
     * Get Transporter Adapter
     *
     * @return iConnection
     */
    function transporter()
    {
        // TODO: Implement transporter() method.
    }


    // ...

    /**
     * @return AbstractOptions
     */
    function inOptions()
    {
        // TODO: Implement inOptions() method.
    }

    /**
     * Get An Bare Options Instance
     *
     * ! it used on easy access to options instance
     *   before constructing class
     *   [php]
     *      $opt = Filesystem::optionsIns();
     *      $opt->setSomeOption('value');
     *
     *      $class = new Filesystem($opt);
     *   [/php]
     *
     * @param null|mixed $builder Builder Options as Constructor
     *
     * @return AbstractOptions
     */
    static function newOptions($builder = null)
    {
        // TODO: Implement newOptions() method.
    }
}
