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
     *
     * Convert an object to an array
     *
     * @param    object  $objects The object to convert
     * @return   array
     *
     */
    public function objectToArray($objects )
    {
        if( !is_object( $objects ) && !is_array( $objects ) )
        {
            return $objects;
        }
        if( is_object( $objects ) )
        {
            $objects = get_object_vars( $objects );
        }
        if(is_array($objects)){
            foreach($objects as $key => $object){
                $objects [ $key ] = $this->objectToArray( $object );
            }
        }
        return $objects;
    }

    /**
     * get configs and generate soap createSession request
     * @param $arguments
     * @return array
     */
    protected function makeRequestCreateSession($arguments)
    {
        return ["CreateSession" => [
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