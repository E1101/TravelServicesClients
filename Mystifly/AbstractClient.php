<?php
namespace Tsp\Mystifly;

use Poirot\ApiClient\AbstractClient as BaseClient;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Request\Method;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Std\Interfaces\ipOptionsProvider;
use Poirot\Std\Interfaces\Struct\iDataStruct;
use Tsp\Mystifly\Interfaces\iMystifly;

abstract class AbstractClient extends BaseClient
    implements ipOptionsProvider
    , iMystifly
{
    /** @var MystiflyOptionsData */
    protected $options;

    /**
     * Reservation constructor.
     * @param iDataStruct|array $options
     */
    function __construct($options = null)
    {
        if ($options != null){
            $this->optsData()->from($options);
        }
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
            'account_number' => $this->optsData()->getAccountNumber(),
            'user_name'      => $this->optsData()->getUserName(),
            'password'       => $this->optsData()->getPassword(),
            'target'         => $this->optsData()->getTarget(),
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
        $inputs['Session'] =  $this->optsData()->getSession()['Session'];

        $method->setArguments( $inputs );

        return $this->call($method);
    }

    /**
     * revalidate selected flight
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function airRevalidate($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['Session'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * get selected flight fare rules ( new implementation )
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function fareRules1_1($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['Session'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * book selected flight with passengers information
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function bookFlight($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * cancel given book reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function cancelBooking($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * order given booking reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function ticketOrder($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * get flight detail from given ticket id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function tripDetails($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * add some note to selected booking reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function addBookingNotes($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }


    /**
     * add some note to selected booking reference id
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function airBookingData($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * get specific category message queues
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function messageQueues($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     * remove selected queue messages
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function removeMessageQueues($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     *
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function multiAirRevalidate($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    /**
     *
     *
     * @param array $inputs
     *
     * @return iResponse
     */
    function multiAirBookFlight($inputs)
    {
        $method = new Method(['method' => __FUNCTION__]);
        $inputs['SessionId'] =  $this->optsData()->getSession()['Session'];
        $inputs['Target'] =  $this->optsData()->getTarget();

        $method->setArguments($inputs);

        return $this->call($method);
    }

    // Client Implementation:

    /**
     * Get Transporter Adapter
     *
     * @return iConnection
     */
    abstract function transporter();

    // options:

    /**
     * @return MystiflyOptionsData
     */
    function optsData()
    {
        if (!$this->options)
            $this->options = self::newOptsData();

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
     * @param null|mixed $builder Builder Options as Constructor
     *
     * @return MystiflyOptionsData
     */
    static function newOptsData($builder = null)
    {
        return (new MystiflyOptionsData())->from($builder);
    }
}
