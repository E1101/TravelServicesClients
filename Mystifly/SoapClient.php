<?php
namespace Tsp\Mystifly;

use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\Connection\Interfaces\iConnection;
use Tsp\Mystifly\ApiClient\SoapTransporter;
use Tsp\Mystifly\ApiClient\SoapPlatform;

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
     * @return iConnection
     */
    function transporter()
    {
        if (!$this->transporter)
            // with options build transporter
            $this->transporter = new SoapTransporter(array_merge(
                $this->inOptions()->getConnectionConfig()
                , ['server_url' => $this->inOptions()->getServerUrl()]
            ));

        return $this->transporter;
    }
}
