<?php
/**
 * Created by PhpStorm.
 * User: Seyyed Sajad Kashizadeh
 * Date: 2/1/16
 * Time: 12:19 PM
 */

namespace Tsp\Mystifly\ApiClient;


trait SoapHelperTrait
{
    /**
     *
     * Convert an object to an array
     *
     * @param    object  $objects The object to convert
     * @return   array
     *
     */
    function toArray($objects )
    {
        if( !is_object( $objects ) && !is_array( $objects ) )
        {
            return $objects;
        }
        if( is_object( $objects ) )
        {
            $objects = get_object_vars( $objects );
        }
        if( is_array( $objects ) )
        {
            foreach( $objects as $key => $object ){
                $objects [ $key ] = $this->toArray($object);
            }
        }

        return $objects;
    }

}