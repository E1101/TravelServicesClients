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

    static function multiAirRevalidateRequest()
    {
        return [
            "FareSourceCodes"	=>	[
                                        'MTMwMDAwMSY3NjVKJjFHJjk5ODY4OWIzLTgyNzMtNGEzNy1iYWE1LTlkMmNlODM1NGRkOSY3NjVKJlR3bw==' ,
                                        'MTMwMDAyNCY3NjVKJjFHJjk5ODY4OWIzLTgyNzMtNGEzNy1iYWE1LTlkMmNlODM1NGRkOSY3NjVKJlR3bw==' ,
                                    ],
            "SessionId"			=>	'56aa685e-ea9d-4d36-9861-34d1fdf6a51f' ,
            "Target"			=>	'Test'
        ];
    }

    static function bookFlightRequest()
    {
        return [
            "ClientMarkup"      =>  '0',
            "FareSourceCode"    =>  'MTMwMDAwMiY3NjVKJjFHJjFjZGU2MWQ1LTMwYmQtNDUyZC1iOGMzLTFmZDZmMmU4MWUyMSY3NjVKJlR3bw==',
            "SessionId"         =>  'f7406e7a-d9de-49dd-92f5-2d311c83452f' ,
            "Target"            =>  'Test',
            "TravelerInfo"=>[
                "AirTravelers"=>[
                    "AirTraveler"   =>[
                        [
                            'PassengerType'=>'ADT',
                            'Gender'=>'M',
                            'PassengerName'=>[
                                'PassengerTitle' => 'MR',
                                'PassengerFirstName' => 'sajad',
                                'PassengerLastName' => 'kashizadeh',
                            ],
                            'DateOfBirth'=>'1991-05-22T12:00:00',
                            'Passport'=>[
                                'Country'           =>'IR',
                                'ExpiryDate'        =>'2017-02-14T10:00:00',
                                'PassportNumber'    =>'IR98745612',
                            ]
                        ]
                    ]
                ],
                "CountryCode"	=>'98',
                "Email"			=>'s.kashizadeh@gmail.com',
                "AreaCode"		=>'21',
                "PhoneNumber"	=>'9308645858',
            ]
        ];
    }

    static function cancelBookingRequest()
    {
        return [
            "SessionId"         =>  'f7406e7a-d9de-49dd-92f5-2d311c83452f' ,
            "UniqueID"          =>  'MF08984016',
            "Target"            =>  'Test',
        ];
    }

    static function fareRules1_1Request()
    {
        return [
            "FareSourceCode"	=>	'MTMwMDAwMiY3NjVKJjFHJjFjZGU2MWQ1LTMwYmQtNDUyZC1iOGMzLTFmZDZmMmU4MWUyMSY3NjVKJlR3bw==' ,
            "SessionId"			=>	'f7406e7a-d9de-49dd-92f5-2d311c83452f' ,
            "Target"			=>	'Test'
        ];
    }

    static function ticketOrderRequest()
    {
        return [
            "FareSourceCode"	=>	'MTMwMDAwMiY3NjVKJjFHJjFjZGU2MWQ1LTMwYmQtNDUyZC1iOGMzLTFmZDZmMmU4MWUyMSY3NjVKJlR3bw==' ,
            "SessionId"			=>	'f7406e7a-d9de-49dd-92f5-2d311c83452f' ,
            "UniqueID"			=>	'MF08984016',
            "Target"			=>	'Test',
        ];
    }

    static function tripDetailsRequest()
    {
        return [
            "SessionId"			=>	'f7406e7a-d9de-49dd-92f5-2d311c83452f' ,
            "UniqueID"			=>	'MF08950816',
            "Target"			=>	'Test',
        ];
    }

    static function addBookingNotesRequest()
    {
        return [
            "Notes"			    =>	[
                                        'mesle tamume alam',
                                        'hale manam kharabe'
                                    ],
            "SessionId"			=>	'3e46d96e-a05c-41b7-89d6-a59e6521f316',
            "UniqueID"			=>	'MF08984316',
            "Target"			=>	'Test',
        ];
    }

    static function airBookingDataRequest()
    {
        return [
            "SessionId"			=>	'3e46d96e-a05c-41b7-89d6-a59e6521f316',
            "UniqueID"			=>	'MF08984316',
            "Target"			=>	'Test',
        ];
    }

    static function messageQueuesRequest()
    {
        return [
            "SessionId"			=>	'3e46d96e-a05c-41b7-89d6-a59e6521f316',
            'CategoryId'        =>  'Cancelled',
            "Target"			=>	'Test',
        ];
    }

    static function removeMessageQueuesRequest()
    {
        return [
            'Items'             => [
                                        'Item'=>[
                                            'CategoryId'=>'Cancelled',
                                            'UniqueId'  =>'MF08983616'
                                        ]
                                    ],
            "SessionId"			=>	'3e46d96e-a05c-41b7-89d6-a59e6521f316',
            "Target"			=>	'Test',
        ];
    }

}
