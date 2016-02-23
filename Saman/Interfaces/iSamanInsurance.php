<?php
namespace Tsp\Saman\Interfaces;

use Poirot\ApiClient\Interfaces\Response\iResponse;
use Tsp\Saman\InsuranceService\DataField\InsuranceData;

interface iSamanInsurance
{
    /**
     * This method is used to get the credit limit and the remaining credit
     *
     * !! the credit must be more than price of policy
     *
     * @return iResponse
     */
    function getCredit();

    /**
     * To get the list of countries with their internal system code
     *
     * @return iResponse
     */
    function getCountries();

    /**
     * Get Country Detail With Its Code
     *
     * - detail will include country name & region code
     *
     * @param integer $countryCode
     *
     * @return iResponse
     */
    function getCountry($countryCode);

    /**
     * This method is used to get a list of the underwriting policy
     * for standard time of Saman Insurance
     *
     * @return iResponse
     */
    function getDurationsOfStay();

    /**
     * The output of this method is a list of plans with their
     * coverage and prices of each plan
     *
     * @param int    $countryCode    Target country code
     * @param string $birthDate      yyyy-mm-ddThh:mm:ss
     * @param int    $durationOfStay Duration of stay
     *
     * @return iResponse
     */
    function getPlansWithDetail($countryCode, $birthDate, $durationOfStay);

    /**
     * Get list of coverage and prices for requested plan
     *
     * @param int $planCode
     *
     * @return iResponse
     */
    function getPlan($planCode);

    /**
     * To get insurance price
     *
     * @param int    $countryCode    Target country code
     * @param string $birthDate      yyyy-mm-ddThh:mm:ss
     * @param int    $durationOfStay Duration of stay
     * @param int    $planCode       Requested plan code
     *
     * @return iResponse
     */
    function getPriceInquiry($countryCode, $birthDate, $durationOfStay, $planCode);

    /**
     * Used To Record Insurance Policy
     *
     * !! registered record is temporary for 2days
     *    and must be confirmed with related method
     *
     * @param array|InsuranceData $insuranceData
     *
     * @return iResponse
     */
    function registerInsurance($insuranceData);

    /**
     * Confirm a registered Insurance Record
     *
     * @param int $serialNo Serial of insurance return after register
     *
     * @return iResponse
     */
    function confirmInsurance($serialNo);

    /**
     * Used to retrieve Insurance information of registered letter
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function getInsurance($serialNo);

    /**
     * Get Address of generated PDF file for confirmed insurance
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function getInsurancePrintInfo($serialNo);

    /**
     * Cancel Insurance
     *
     * @param int $serialNo
     *
     * @return iResponse
     */
    function cancelInsurance($serialNo);

    /**
     * Registration information for the insured
     *
     * @param array $customerData
     *
     * @return iResponse
     */
    function registerCustomerData($customerData);

    /**
     * Registration information for the insured
     *
     * @param string      $nationalCode   Melli code of insured
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $firstNameLatin
     * @param string      $lastNameLatin
     * @param int         $gender         1=male, 2=female
     * @param string      $birthDate      yyyy-mm-ddThh:mm:ss
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
        $mobile     = null,
        $email      = null,
        $postCode   = null
    );

    /**
     * To Receive information of the registered customer
     *
     * @param string $nationalCode
     *
     * @return iResponse
     */
    function getCustomer($nationalCode);

    /**
     * To get related insurance to insured
     *
     * @param string $nationalCode
     * @param string $passportNo
     * @param int    $countryCode
     *
     * @return iResponse
     */
    function getCustomerInsurances($nationalCode, $passportNo, $countryCode);


    // ...

    /**
     * Used To Record Customer Information For Group Insurance Policies
     *
     * !! to edit previous record you can call this method again
     *
     * @param int $countryCode    Target country code
     * @param int $durationOfStay Duration of stay
     * @param int $travelKind     Visa Type, 1=single|2=multi
     * @param int $planCode       Insurance plan code
     *
     * @return iResponse
     */
    function groupInsuranceRegister($countryCode, $durationOfStay, $travelKind, $planCode);

    /**
     * It Will Delete Registered Group Insurance Record
     *
     * @return iResponse
     */
    function groupInsuranceDelete();

    /**
     * The Method For The Issuance Of Insurance Policies Included In-
     * The Provisional List And Issuing Insurance Group Is Completed
     *
     * @return iResponse
     */
    function groupInsuranceConfirm();

    /**
     * Retrieve Insurance Policies List From Temporary
     *
     * @return iResponse
     */
    function groupInsuranceDetailList();

    /**
     * Add New Insurance Policy To Temporary Group List
     *
     * @param array|InsuranceData $insuranceData
     *
     * @return iResponse
     */
    function groupInsuranceDetailAdd($insuranceData);

    /**
     * Delete Insurance Policy From Temporary Group List
     * 
     * @param string $nationalCode
     *
     * @return iResponse
     */
    function groupInsuranceDetailDelete($nationalCode);
}
