<?php
namespace Tsp\Irangardi\Interfaces;

use Poirot\ApiClient\Interfaces\Response\iResponse;

interface iIRHotel
{
    /**
     * To receive the list of cities/code that exist in our system.
     *
     * @return iResponse
     */
    function getCityList();

    /**
     * To Get Hotels List In A City
     * @param string $CityCod
     * @return iResponse
     */
    function getHotelListCity($CityCod);

    /**
     * Get All Available Hotels
     * @return iResponse
     */
    function getAllHotelList();

    /**
     * Get Detail Properties Of Hotel By HotelCode
     * @param string $HotelCod
     * @return iResponse
     */
    function getHotelProperties($HotelCod);

    /**
     * Retrieve Available Room Types Of Hotel
     * @param $HotelCod
     * @return iResponse
     */
    function getRoomList($HotelCod);

    /**
     * Get Room Price For A Given Date
     *
     * @param string $HotelCod Hotel Code
     * @param string $Dat     Jalali Date in "yyyymmdd" format
     * @param int $RoomTypeCod The Room Code From getRoomList Method
     *                         if not passed it will return all available
     *                         rooms price
     * @return iResponse
     */
    function getRoomPriceListOneDate($HotelCod, $Dat, $RoomTypeCod = 0);

    /**
     * Retrieve The Price For A Room On A Specific Date
     * @param string $HotelCod    Hotel Code
     * @param string $Dat         Jalali Date in "yyyymmdd" format
     * @param int    $RoomTypeCod The Room Code From getRoomList Method
     * @param int    $ResLong     Stay Duration, exp 3 mean three night long
     * @return iResponse
     */
    function getRoomPriceListDateResLong($HotelCod, $Dat, $RoomTypeCod, $ResLong = 1);

    /**
     * Reserve Room As Temporary For 15min
     *
     * ! it must approved with another related method calls
     *
     * @param string $HotelCod    Hotel Code
     * @param string $StDat       Jalali Date in "yyyymmdd" format
     * @param int    $RoomTypeCod The Room Code From getRoomList Method
     * @param int    $ResLong     Stay Duration, exp 3 mean three night long. (1~10)
     * @param int    $NumRoom     Number Of Rooms To Reserve (1~10)
     *
     * @return iResponse Exception or Reserve Number
     */
    function reserveRoom($HotelCod, $StDat, $RoomTypeCod, $ResLong = 1, $NumRoom = 1);

    /**
     * Insert Data Form Reserved Temporary Room
     *
     * !! it must be approved by calling another related method
     *    to finalize
     *
     * @param int              $ReserveNo Reserve Number after ReserveRoom Method Call
     * @param array            $Rooms     Room Type Codes [1, 2, 3]
     * @param array            $CustData  Customer Information [..
     *                              ['name'=>.., 'family'=>.., 'melli_code'=>.., 'birth_date'=>yyymmdd]
     *                         ..]
     * @param string           $MobNo     Mobile Number For Emergency Calls
     * @param string|array     $PhoneNo   Phone Number or [$phone1, $phone2]
     * @param $Address
     * @param string           $Email
     * @param string           $ZipCode
     * @param string           $Dsc
     * @param array            $FlightInfo ['‫‪RaftAirLine'=>..,'FlightNoRaft'=>..,'TimeRaft'=>..,'MasirRaft'=>..
     *                                      'FlightNoRaft'=>.., 'TimeRaft'=>.., 'MasirRaft'=>.., 'BargashtAirLine'=>..,
     *                                      'FlightNoBargasht'=>.., 'TimeBargasht'=>.., 'MasirBargasht'=>..
     *
     * @return iResponse
     */
    function insertNamReserveRoomTemporary(
        $ReserveNo
        , array $Rooms
        , array $CustData
        , $MobNo
        , $PhoneNo
        , $Address
        , $Email = null
        , $ZipCode = null
        , $Dsc = null
        , $FlightInfo = null
    );

    /**
     * @return iResponse
     */
    function foroshRoomFromTemporary();
}
