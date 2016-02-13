<?php
namespace Tsp\Saman;

use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Request\Method;
use Poirot\Connection\Interfaces\iConnection;
use Tsp\Mystifly\ApiClient\SoapTransporter;
use Tsp\Saman\InsuranceService\InsuranceServiceOpts;
use Tsp\Saman\InsuranceService\SoapPlatform;
use tsp\Saman\Interfaces\iSamanInsurance;

class InsuranceService extends AbstractClient
    implements iSamanInsurance
{
    // Client API:


    // Client Implementation:
    /** @var InsuranceServiceOpts */
    protected $options;

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
     * @return InsuranceServiceOpts
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
     * @return InsuranceServiceOpts
     */
    static function newOptions($builder = null)
    {
        return new InsuranceServiceOpts;
    }


    protected function newMethod($methodName, array $args = null)
    {
        $method = new Method;

        $method->setMethod($methodName);

        ## account data options
        ## these arguments is mandatory on each call
        $defAccParams = [
            'OprCod'  => $this->inOptions()->getOprCod(),
            'CustCod' => $this->inOptions()->getCustCod(),
            'PID'     => $this->inOptions()->getPID(),
            'Mojavez' => $this->inOptions()->getMojavez(),
        ];

        $args = ($args !== null) ? array_merge($defAccParams, $args) : $defAccParams;
        $method->setArguments($args);

        return $method;
    }
}
