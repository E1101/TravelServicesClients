<?php
namespace Tsp\Mystifly;

use Poirot\ApiClient\AbstractClient as BaseClient;
use Poirot\ApiClient\Interfaces\iTransporter;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Request\Method;
use Poirot\Core\AbstractOptions;
use Poirot\Core\Interfaces\iDataSetConveyor;
use Poirot\Core\Interfaces\iOptionsProvider;
use Tsp\Mystifly\Interfaces\iMystifly;

abstract class AbstractClient extends BaseClient
    implements iOptionsProvider
    , iMystifly
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
        $method->setArguments([
            'account_number' => $this->inOptions()->getAccountNumber(),
            'user_name'      => $this->inOptions()->getUserName(),
            'password'       => $this->inOptions()->getPassword(),
            'target'         => $this->inOptions()->getTarget(),
        ]);

        return $this->call($method);
    }

    /**
     * get list of available flight from mystifly webservice
     *
     * @param $inputs
     * @return iResponse
     */
    function airLowFareSearch($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $method->setArguments($inputs);

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
     * Get Transporter Adapter
     *
     * @return iTransporter
     */
    abstract function transporter();

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
