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
}