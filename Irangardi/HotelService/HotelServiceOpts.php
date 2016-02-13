<?php
namespace Tsp\Irangardi\HotelService;

use Poirot\Core\AbstractOptions;

class HotelServiceOpts extends AbstractOptions
{
    protected $serverUrl = 'http://94.182.216.5/FarasooMarcopoloHotel/Service.asmx?wsdl';

    ## account data
    protected $OprCod  = '1000001738';
    protected $CustCod = '000695';
    protected $PID     = '21359906';
    protected $Mojavez = '754633';

    // Transporter Specific Options
    ## soap context options
    protected $connConfig = [
        'connection'=>'close',
    ];


    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @param string $serverUrl
     * @return $this
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;
        return $this;
    }


    /**
     * @return array
     */
    public function getConnConfig()
    {
        return $this->connConfig;
    }

    /**
     * @param array $connConfig
     * @return $this
     */
    public function setConnConfig($connConfig)
    {
        $this->connConfig = $connConfig;
        return $this;
    }

    /**
     * @return string
     */
    public function getOprCod()
    {
        return $this->OprCod;
    }

    /**
     * @param string $OprCod
     */
    public function setOprCod($OprCod)
    {
        $this->OprCod = $OprCod;
    }

    /**
     * @return string
     */
    public function getCustCod()
    {
        return $this->CustCod;
    }

    /**
     * @param string $CustCod
     */
    public function setCustCod($CustCod)
    {
        $this->CustCod = $CustCod;
    }

    /**
     * @return string
     */
    public function getPID()
    {
        return $this->PID;
    }

    /**
     * @param string $PID
     */
    public function setPID($PID)
    {
        $this->PID = $PID;
    }

    /**
     * @return string
     */
    public function getMojavez()
    {
        return $this->Mojavez;
    }

    /**
     * @param string $Mojavez
     */
    public function setMojavez($Mojavez)
    {
        $this->Mojavez = $Mojavez;
    }
}
