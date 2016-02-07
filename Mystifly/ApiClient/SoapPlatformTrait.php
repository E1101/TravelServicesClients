<?php
/**
 * Created by PhpStorm.
 * User: Seyyed Sajad Kashizadeh
 * Date: 1/31/16
 * Time: 11:02 AM
 */

namespace Tsp\Mystifly\ApiClient;


use Poirot\ApiClient\Interfaces\Response\iResponse;
use Tsp\Mystifly\Exception\InvalidSessionException;
use Tsp\Mystifly\Util;

trait SoapPlatformTrait
{
    /**
     * get configs and generate soap createSession request
     * @param $arguments
     * @return array
     */
    protected function makeRequestCreateSession($arguments)
    {
        return [
            "CreateSession" => [
                'rq' => [
                    "AccountNumber" => $arguments['account_number'],
                    "UserName"      => $arguments['user_name'],
                    "Password"      => $arguments['password'],
                    "Target"        => $arguments['target'],
                ]
            ]
        ];
    }

    /**
     * get configs and generate soap createSession request
     * @param $arguments
     * @return array
     */
    protected function makeRequestAirLowFareSearch($arguments)
    {
        return
            [
                'AirLowFareSearch' => [
                    "rq" => [
                        "OriginDestinationInformations" =>  $arguments['OriginDestinationInformations'],
                        "PassengerTypeQuantities" 	    => 	[
                                                                "PassengerTypeQuantity"=> $this->__passengerGenerator($arguments)
                                                            ],
                        "PricingSourceType" 		    =>	$arguments['PricingSourceType'],
                        "RequestOptions"			    =>	$arguments['RequestOptions'] ,
                        "SessionId"					    =>	$arguments['Session'] ,
                        "IsRefundable"				    =>	$arguments['IsRefundable'] ,
                        "NearByAirports"			    =>	$arguments['NearByAirports'] ,
                        "TravelPreferences"             =>  $arguments['TravelPreferences'] ,
                    ]
                ]
            ];
    }

    /**
     * generate array of passengers
     * @param $Inputs
     * @return array | passengers array
     */
    protected function __passengerGenerator($Inputs){
        // generate empty array for output
        $passengers = array();

        foreach($Inputs['TravelerInfoSummary']['AirTravelerAvail']['PassengerTypeQuantities'] as $key => $Passenger){
            // get list of passengers
            $passengers[] = $Passenger['PassengerTypeQuantity'];
        }

        return count($passengers)==1?$passengers[0]:$passengers;
    }

    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestAirRevalidate($arguments)
    {
        return
            [
                'AirRevalidate' => [
                    "rq" => [
                        "FareSourceCode"	=>	$arguments [ 'FareSourceCode' ],
                        "SessionId"			=>	$arguments [ 'Session' ],
                        "Target"			=>	$arguments [ 'Target' ]
                    ]
                ]
            ];
    }

    /**
     * get configs and generate soap multiAirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestMultiAirRevalidate($arguments)
    {
        return
            [
                'MultiAirRevalidate' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestBookFlight($arguments)
    {
        return
            [
                'BookFlight' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestCancelBooking($arguments)
    {
        return
            [
                'CancelBooking' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestFareRules1_1($arguments)
    {
        return
            [
                'FareRules1_1' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestTicketOrder($arguments)
    {
        return
            [
                'TicketOrder' => [
                    "rq" => $arguments
                ]
            ];
    }


    /**
     * get configs and generate soap AirRevalidate request
     * @param $arguments
     * @return array
     */
    protected function makeRequestTripDetails($arguments)
    {
        return
            [
                'TripDetails' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap add booking note request
     * @param $arguments
     * @return array
     */
    protected function makeRequestAddBookingNotes($arguments)
    {
        return
            [
                'AddBookingNotes' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap AirBookingData request
     * @param $arguments
     * @return array
     */
    protected function makeRequestAirBookingData($arguments)
    {
        return
            [
                'AirBookingData' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap messageQueues request
     * @param $arguments
     * @return array
     */
    protected function makeRequestMessageQueues($arguments)
    {
        return
            [
                'MessageQueues' => [
                    "rq" => $arguments
                ]
            ];
    }

    /**
     * get configs and generate soap messageQueues request
     * @param $arguments
     * @return array
     */
    protected function makeRequestRemoveMessageQueues($arguments)
    {
        return
            [
                'RemoveMessageQueues' => [
                    "rq" => $arguments
                ]
            ];
    }


    /**
     * handle mystifly exceptions
     * @param iResponse $response
     * @return array
     */
    protected function exceptionHandler($response)
    {
        $result = Util::toArray($response->getRawBody());
        
        if(!isset($result['Errors']['Error'])){
            return $response;
        }

        if(!isset($result['Errors']['Error']['0'])){
            $temp = $result['Errors']['Error'];
            unset($result['Errors']['Error']);
            $result['Errors']['Error']['0'] = $temp;
        }

        foreach($result['Errors']['Error'] as $Error){
            switch($Error['Code']){
                // SessionId cannot be null
                case 'ERSER001':
                case 'ERSMP001':
                case 'ERSRV001':
                case 'EROTK001':
                case 'ERAQT001':
                case 'ERTDT001':
                case 'ERMQU001':
                case 'ERRMQ001':
                case 'ERABN001':
                case 'ERABD001':
                case 'ERMAR001':
                case 'ERMAB001':
                case 'ERIFS001':
                case 'ERPAY001':
                //Invalid SessionId
                case 'ERSER002':
                case 'ERSMP002':
                case 'ERSRV002':
                case 'EROTK002':
                case 'ERAQT002':
                case 'ERTDT002':
                case 'ERMQU003':
                case 'ERRMQ006':
                case 'ERABN006':
                case 'ERABD002':
                case 'ERMAR002':
                case 'ERMAB002':
                case 'ERIFS002':
                case 'ERPAY002':
                    // chain exception to previous exceptions
                    $response->setException(new InvalidSessionException(
                        $Error['Message'],
                        null,
                        $response->hasException() ? $response->hasException() : null
                    ));
                    break;
                default:
                    // chain exception to previous exceptions
                    $response->setException(new \Exception(
                        $Error['Message'],
                        null,
                        $response->hasException() ? $response->hasException() : null
                    ));
                    break;
            }
        }
//        echo "<pre>";
//        var_dump($response);die();
        return $response;
    }


}