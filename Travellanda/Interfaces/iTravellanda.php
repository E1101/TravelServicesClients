<?php
namespace Tsp\Travellanda\Interfaces;

use Poirot\ApiClient\Interfaces\Response\iResponse;

interface iTravellanda
{
    /**
     * Receive the list of countries that exist in Travellanda
     *
     * @return iResponse
     */
    function getCountries();

    /**
     * To receive the list of cities that exist in our system.
     *
     * You can use the optional "CountryCode" element to get the
     * cities of a single country.
     *
     * [code:]
     *  getCities('GB'); // cities of britain
     *  getCities();     // all cities
     * [code]
     *
     * @param string $countryCode 2 letter ISO code of the country
     *
     * @return iResponse
     */
    function getCities($countryCode = null);

    /**
     * To receive the list of hotels in a single country or city.
     *
     * Send "CountryCode" for hotels in that country,
     * or "CityId" for hotels in that city.
     * You cannot send both.
     *
     * @param string  $countryCode 2 letter ISO code of the country
     * @param integer $cityID      Travellanda internal id of the city
     *
     * @return iResponse
     */
    function getHotels($countryCode = null, $cityID = null);

    /**
     * Receive detailed information for the hotel ids you send.
     * You can send up to 250 hotel ids in a single request.
     * !! Non existing hotel ids do not return any information.
     *
     * [code:]
     *  getHotelDetails(1009075);
     *  getHotelDetails([1009075, 1000740])
     * [code]
     *
     * @param int|array $hotelId HotelID or list of HotelIds
     *
     * @return iResponse
     */
    function getHotelDetails($hotelId);

    /**
     * To make a booking, first you need to start a search for a
     * selected city or hotel.
     * Available hotels matching your search criteria are returned
     * in the search response with price and room information.
     *
     * @param string $CheckInDate  Check-in date in the format YYYY-MM-DD
     * @param string $CheckOutDate Check-out date in the format YYYY-MM-DD
     * @param array  $Rooms        ['Room' => ['NumAdults' => 2, (optional) 'Children' => ['ChildAge' => 4, 6] ] ]
     * @param string $Nationality  2 letter ISO code of the country
     * @param int    $CityId       Travellanda internal id of the city
     * @param int    $HotelId      Travellanda internal id of the hotel
     * @param string $Currency     3 letter code of the currency, (EUR|USD|GBP|HKD)
     *
     * @return iResponse
     */
    function hotelSearch(
        $CheckInDate,
        $CheckOutDate,
        array $Rooms,
        $Nationality,
        $CityId = null,
        $HotelId = null,
        $Currency = null
    );

    /**
     * After a hotel search, an option can be selected to be booked.
     * Before making a booking for that option, its cancellation policies,
     * restrictions and important messages must be get using this method.
     *
     * @param integer $OptionId The id of the option that you want to get the policies of
     *
     * @return iResponse
     */
    function hotelPolicies($OptionId);

    /**
     * To make a booking for your selected option
     *
     * @param integer $OptionId      The id of the option that you want to make a booking for
     *                               It is returned in the hotel search response.
     * @param string  $YourReference The reference that you want to assign to this booking
     *                               Its value can be empty and does not have to be unique.
     * @param array   $Rooms         ['Room' => ['RoomId' => ..,
     *                                 'PaxNames' => [
     *                                  'AdultName' => [
     *                                     ['Title'=>'Mr', 'FirstName'=>.., 'LastName'=>..],
     *                                     ...
     *                                   ],
     *                                  'AdultName' => [..]
     *                                  'ChildName' => [
     *                                     ['FirstName'=>.., 'LastName'=>..]
     *                                  ]
     *                                  'ChildName' => [..]
     *                                 ]
     *                              ]]
     *
     * @return iResponse
     */
    function hotelBooking(
        $OptionId,
        $YourReference,
        array $Rooms
    );

    /**
     * To search and receive the details of your existing bookings.
     *
     * You can search for your bookings by booking reference,
     * your reference, booking date and check-in date.
     * Up to 100 bookings are returned in a single response.
     * You can combine the search parameters to narrow down the search results.
     *
     * @param string $BookingReference Search by Travellanda reference
     * @param string $YourReference    Search by your reference
     * @param array  $BookingDates     ['BookingDateStart'=>.., 'BookingDateEnd'=>..]
     * @param array  $CheckInDates     ['CheckInDateStart'=>.., 'CheckInDateEnd'=>..]
     *
     * @return iResponse
     */
    function hotelBookingDetails(
        $BookingReference = null,
        $YourReference = null,
        $BookingDates = null,
        $CheckInDates = null
    );

    /**
     * To cancel an existing booking.
     *
     * Bookings whose cancellation deadline is in the past
     * cannot be cancelled immediately, and their Request Status
     * is set to 'Cancellation Pending'.
     *
     * @param string $BookingReference Travellanda reference of the booking
     *
     * @return iResponse
     */
    function hotelBookingCancel($BookingReference);
    
}
