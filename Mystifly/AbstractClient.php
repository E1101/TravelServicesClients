<?php
namespace Tsp\Mystifly;

use Poirot\ApiClient\AbstractClient as BaseClient;
use Poirot\ApiClient\Interfaces\iConnection;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Request\Method;
use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iDataSetConveyor;
use Poirot\Core\Interfaces\iOptionsProvider;

abstract class AbstractClient extends BaseClient
    implements iOptionsProvider
{
    /** @var MystiflyOptions */
    protected $options;

    /**
     * Reservation constructor.
     * @param iDataSetConveyor|array $options
     */
    function __construct($options = null)
    {
        if ($options != null)
            $this->inOptions()->from($options);
    }


    // Client API:

    /**
     * generate session base on mystifly session generator
     *
     * @return iResponse
     */
    function createSession()
    {
        $method = new Method(['method' => __FUNCTION__]);

        // add mystifly config to method arguments
        $method->setArguments($this->inOptions()->toArray());

        return $this->call($method);
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
//    abstract function platform();

    /**
     * Get Connection Adapter
     *
     * @return iConnection
     */
    abstract function connection();
    // options:

    /**
     * @return MystiflyOptions
     */
    function inOptions()
    {
        if (!$this->options)
            $this->options = self::newOptions();

        return $this->options;
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
     * @return MystiflyOptions
     */
    static function newOptions()
    {
        return new MystiflyOptions;
    }
}
