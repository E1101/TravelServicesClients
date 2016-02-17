<?php
namespace Tsp\Saman;

use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\ApiClient\Request\Method;
use Poirot\Connection\Interfaces\iConnection;
use tsp\Saman\InsuranceService\DataField\InsuranceData;
use Tsp\SoapTransporter;
use Tsp\Saman\InsuranceService\InsuranceServiceOpts;
use Tsp\Saman\InsuranceService\SoapPlatform;
use Tsp\Saman\Interfaces\iSamanInsurance;

class InsuranceService extends AbstractClient
    implements iSamanInsurance
{
    /** @var InsuranceServiceOpts */
    protected $options;


    // Client API:

    /**
     * This method is used to get the credit limit and the remaining credit
     *
     * !! the credit must be more than price of policy
     *
     * @return iResponse
     */
    function getCredit()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * To get the list of countries with their internal system code
     *
     * @return iResponse
     */
    function getCountries()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * Get Country Detail With Its Code
     *
     * - detail will include country name & region code
     *
     * @param integer $countryCode
     *
     * @return iResponse
     */
    function getCountry($countryCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'countryCode' => $countryCode
        ]);
        return $this->call($method);
    }

    /**
     * This method is used to get a list of the underwriting policy
     * for standard time of Saman Insurance
     *
     * @return iResponse
     */
    function getDurationsOfStay()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * The output of this method is a list of plans with their
     * coverage and prices of each plan
     *
     * @param int    $countryCode    Target country code
     * @param string $birthDate      yyyy-mm-ddThh:mm:ss (1983-08-13)
     * @param int    $durationOfStay Duration of stay
     *
     * @return iResponse
     */
    function getPlansWithDetail($countryCode, $birthDate, $durationOfStay)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'countryCode'    => $countryCode,
            'birthDate'      => $birthDate,
            'durationOfStay' => $durationOfStay,

        ]);
        return $this->call($method);
    }

    /**
     * Get list of coverage and prices for requested plan
     *
     * @param int $planCode
     *
     * @return iResponse
     */
    function getPlan($planCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'planCode' => $planCode,

        ]);
        return $this->call($method);
    }

    /**
     * To get insurance price
     *
     * @param int    $countryCode    Target country code
     * @param string $birthDate      yyyy-mm-ddThh:mm:ss (1983-08-13)
     * @param int    $durationOfStay Duration of stay
     * @param int    $planCode       Requested plan code
     *
     * @return iResponse
     */
    function getPriceInquiry($countryCode, $birthDate, $durationOfStay, $planCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'countryCode'    => $countryCode,
            'birthDate'      => $birthDate,
            'durationOfStay' => $durationOfStay,
            'planCode'       => $planCode,

        ]);
        return $this->call($method);
    }

    /**
     * Used To Record Insurance Policy
     *
     * !! registered record is temporary for 2days
     *    and must be confirmed with related method
     *
     * @param array|InsuranceData
     *
     * @return iResponse
     */
    function registerInsurance($insuranceData)
    {
        if ($insuranceData instanceof InsuranceData)
            $insuranceData = $insuranceData->toArray(function($key){
                return \Poirot\Std\sanitize_PascalCase($key);
            });

        $method = $this->newMethod(__FUNCTION__, $insuranceData);
        return $this->call($method);
    }

    /**
     * Confirm a registered Insurance Record
     *
     * @param int $serialNo Serial of insurance return after register
     *
     * @return iResponse
     */
    function confirmInsurance($serialNo)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'serialNo' => $serialNo,
        ]);
        return $this->call($method);
    }

    /**
     * Used to retrieve Insurance information of registered letter
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function getInsurance($serialNo)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'serialNo' => $serialNo,
        ]);
        return $this->call($method);
    }

    /**
     * Get Address of generated PDF file for confirmed insurance
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function getInsurancePrintInfo($serialNo)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'serialNo' => $serialNo,
        ]);
        return $this->call($method);
    }

    /**
     * Cancel Insurance
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function cancelInsurance($serialNo)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'serialNo' => $serialNo,
        ]);
        return $this->call($method);
    }

    /**
     * Registration information for the insured
     *
     * @param string $nationalCode Melli code of insured
     * @param string $firstName
     * @param string $lastName
     * @param string $firstNameLatin
     * @param string $lastNameLatin
     * @param int $gender 1=male, 2=female
     * @param string $birthDate yyyy-mm-ddThh:mm:ss
     * @param string|null $birthPlace
     * @param string|null $mobile
     * @param string|null $email
     * @param string|null $postCode
     *
     * @return iResponse
     */
    function registerCustomer(
        $nationalCode,
        $firstName,
        $lastName,
        $firstNameLatin,
        $lastNameLatin,
        $gender,
        $birthDate,
        $birthPlace = null,
        $mobile = null,
        $email = null,
        $postCode = null
    )
    {
        $args = [
            'nationalCode'   => $nationalCode,
            // Note: Saman API has fisrtName instead of firstName on implementation time
            'fisrtName'      => $firstName,
            'lastName'       => $lastName,
            'firstNameLatin' => $firstNameLatin,
            'lastNameLatin'  => $lastNameLatin,
            'gender'         => $gender,
            'birthDate'      => $birthDate,
        ];

        ($birthPlace === null) ?: $args['birthPlace'] = $birthPlace;
        ($mobile === null)     ?: $args['mobile'] = $mobile;
        ($email === null)      ?: $args['email'] = $email;
        ($postCode === null)   ?: $args['postCode'] = $postCode;

        $method = $this->newMethod(__FUNCTION__, $args);
        return $this->call($method);
    }

    /**
     * To Receive information of the registered customer
     *
     * @param string $nationalCode
     *
     * @return iResponse
     */
    function getCustomer($nationalCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'nationalCode' => $nationalCode,
        ]);
        return $this->call($method);
    }

    /**
     * To get related insurance to insured
     *
     * @param string $nationalCode
     * @param string $passportNo
     * @param int $countryCode
     *
     * @return iResponse
     */
    function getCustomerInsurances($nationalCode, $passportNo, $countryCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'nationalCode' => $nationalCode,
            'passportNo'   => $passportNo,
            'countryCode'  => $countryCode,
        ]);
        return $this->call($method);
    }

    /**
     * Used To Record Customer Information For Group Insurance Policies
     *
     * !! to edit previous record you can call this method again
     *
     * @param int $countryCode Target country code
     * @param int $durationOfStay Duration of stay
     * @param int $travelKind Visa Type, 1=single|2=multi
     * @param int $planCode Insurance plan code
     *
     * @return iResponse
     */
    function groupInsuranceRegister($countryCode, $durationOfStay, $travelKind, $planCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'countryCode'    => $countryCode,
            'durationOfStay' => $durationOfStay,
            'travelKind'     => $travelKind,
            'planCode'       => $planCode,
        ]);
        return $this->call($method);
    }

    /**
     * It Will Delete Registered Group Insurance Record
     *
     * @return iResponse
     */
    function groupInsuranceDelete()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * The Method For The Issuance Of Insurance Policies Included In-
     * The Provisional List And Issuing Insurance Group Is Completed
     *
     * @return iResponse
     */
    function groupInsuranceConfirm()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * Retrieve Insurance Policies List From Temporary
     *
     * @return iResponse
     */
    function groupInsuranceDetailList()
    {
        $method = $this->newMethod(__FUNCTION__);
        return $this->call($method);
    }

    /**
     * Add New Insurance Policy To Temporary Group List
     *
     * @param $insuranceData
     *
     * @return iResponse
     */
    function groupInsuranceDetailAdd($insuranceData)
    {
        if ($insuranceData instanceof InsuranceData)
            $insuranceData = $insuranceData->toArray();

        $method = $this->newMethod(__FUNCTION__, $insuranceData);
        return $this->call($method);
    }

    /**
     * Delete Insurance Policy From Temporary Group List
     *
     * @param string $nationalCode
     *
     * @return iResponse
     */
    function groupInsuranceDetailDelete($nationalCode)
    {
        $method = $this->newMethod(__FUNCTION__, [
            'nationalCode'    => $nationalCode,
        ]);
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
        return new InsuranceServiceOpts($builder);
    }


    protected function newMethod($methodName, array $args = null)
    {
        $method = new Method;

        $method->setMethod($methodName);

        ## account data options
        ## these arguments is mandatory on each call
        $defAccParams = [
            'username' => $this->inOptions()->getUsername(),
            'password' => $this->inOptions()->getPassword(),
        ];

        $args = ($args !== null) ? array_merge($defAccParams, $args) : $defAccParams;
        $method->setArguments($args);

        return $method;
    }
}
