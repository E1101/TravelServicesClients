<?php
namespace Tsp\Mystifly;

use Poirot\ApiClient\Interfaces\iConnection;
use Tsp\Mystifly\ApiClient\SoapTransporter;
use Tsp\Mystifly\ApiClient\SoapPlatform;
use Tsp\Mystifly\Interfaces\iResponse;

class SoapClient extends AbstractClient
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
        if (!$this->platform)
            $this->platform = new SoapPlatform($this);

        return $this->platform;
    }

    /**
     * Get Transporter Adapter
     *
     * @return iTransporter
     */
    function transporter()
    {
        if (!$this->transporter)
            // with options build transporter
            $this->transporter = new SoapTransporter($this->inOptions()->getConnectionConfig());

        return $this->transporter;
    }

}
