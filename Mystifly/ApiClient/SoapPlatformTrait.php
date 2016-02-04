<?php
/**
 * Created by PhpStorm.
 * User: Seyyed Sajad Kashizadeh
 * Date: 1/31/16
 * Time: 11:02 AM
 */

namespace Tsp\Mystifly\ApiClient;


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

}