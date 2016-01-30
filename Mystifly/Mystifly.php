<?php
namespace Tsp\Mystifly;

use Ar\Travellanda\Reservation\Platform;
use Ar\Travellanda\Reservation\ReqMethod;
use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Connection\HttpStreamConnection;
use Poirot\ApiClient\Interfaces\iConnection;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iDataSetConveyor;
use Poirot\Core\Interfaces\iOptionsProvider;

class Mystifly extends AbstractClient
    implements iOptionsProvider
{
    /** @var Platform */
    protected $platform;
    /** @var HttpStreamConnection */
    protected $connection;
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
     * Receive the list of countries that exist in Travellanda
     *
     * @return iResponse
     */
    function getCountries()
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);
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
    function platform()
    {
        if (!$this->platform)
            $this->platform = new Platform($this);

        return $this->platform;
    }

    /**
     * Get Connection Adapter
     *
     * @return iConnection
     */
    function connection()
    {
        if (!$this->connection)
            $this->connection = new HttpStreamConnection([
                'context' => [
                    'socket' => [
                        'proxy' => 'tcp://asantravel.com:8000',
                        'request_fulluri' => true,
                    ],
                ]
            ]);

        return $this->connection;
    }

    /**
     * @override
     * @inheritdoc
     *
     * @return iResponse
     */
    function call(iApiMethod $method)
    {
        if (!$method instanceof ReqMethod)
            $method = new ReqMethod($method->toArray());

        $method->setUsername($this->inOptions()->getUsername());
        $method->setPassword($this->inOptions()->getPassword());

        return parent::call($method);
    }

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
