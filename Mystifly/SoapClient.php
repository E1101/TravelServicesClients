<?php
namespace Tsp\Mystifly;

use Tsp\Mystifly\ApiClient\SoapConnection;
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
     * Get Connection Adapter
     *
     * @return iConnection
     */
    function connection()
    {
        if (!$this->connection) {
            // with options build connection
            $this->connection = new SoapConnection($this->inOptions()->getConfigs());
        }

        return $this->connection;
    }
}