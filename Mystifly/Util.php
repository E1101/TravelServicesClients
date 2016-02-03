<?php
namespace Tsp\Mystifly;

class Util
{
    /**
     * Convert an object to an array
     *
     * @param    object  $objects The object to convert
     * @return   array
     */
    static function toArray($objects)
    {
        if( !is_object( $objects ) && !is_array( $objects ) )
            return $objects;

        if( is_object( $objects ) )
            $objects = get_object_vars( $objects );

        if( is_array( $objects ) ) {
            foreach( $objects as $key => $object )
                $objects [ $key ] = self::toArray($object);
        }

        return $objects;
    }
}
