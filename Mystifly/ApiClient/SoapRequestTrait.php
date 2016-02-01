<?php
/**
 * Created by PhpStorm.
 * User: Seyyed Sajad Kashizadeh
 * Date: 1/31/16
 * Time: 11:02 AM
 */

namespace Tsp\Mystifly\ApiClient;


trait SoapRequestTrait
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
        var_dump($arguments);die('salam');
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

    private function makeOriginDestinationInformationArray($arguments)
    {
        var_dump($arguments);die('salam');
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

}