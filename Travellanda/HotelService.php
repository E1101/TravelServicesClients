<?php
namespace Tsp\Travellanda;

use Poirot\Connection\Http\HttpSocketConnection;
use Poirot\Connection\Interfaces\iConnection;
use Tsp\Travellanda\Interfaces\iTravellanda;
use Tsp\Travellanda\Reservation\FakerConnection;
use Tsp\Travellanda\Reservation\Platform;
use Tsp\Travellanda\Reservation\ReqMethod;
use Poirot\ApiClient\AbstractClient;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\Std\Interfaces\Struct\iDataStruct;
use Poirot\Std\Interfaces\ipOptionsProvider;

class HotelService extends AbstractClient
    implements ipOptionsProvider
    , iTravellanda
{
    /** @var Platform */
    protected $platform;
    /** @var HttpSocketConnection */
    protected $transporter;
    /** @var HotelServiceOpts */
    protected $options;

    /**
     * Reservation constructor.
     * @param iDataStruct|array $options
     */
    function __construct($options = null)
    {
        if ($options != null)
            $this->optsData()->from($options);
    }


    // Client API:

    /**
     * Receive the list of countries that exist in Travellanda
     *
     * @return iResponse
     */
    function getCountries()
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);
        return $this->call($method);
    }

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
    function getCities($countryCode = null)
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [];
        ($countryCode === null) ?: $params['CountryCode'] = $countryCode;
        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    function getHotels($countryCode = null, $cityID = null)
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [];
        ($countryCode === null) ?: $params['CountryCode'] = $countryCode;
        ($cityID      === null) ?: $params['CityId']      = $cityID;

        if ($countryCode !== null && $cityID !== null)
            throw new \InvalidArgumentException(
                'You cannot use both CountryCode and CityId'
            );

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    function getHotelDetails($hotelId)
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        if (!is_array($hotelId))
            $hotelId = [$hotelId];

        /*
         * <HotelIds>
         *  <HotelId>1009075</HotelId>
         *  <HotelId>1000740</HotelId>
         *  .....
         * </HotelIds>
         */
        $params = [ 'HotelIds' => ['HotelId' => $hotelId] ];

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    ) {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [
            'CheckInDate'  => $CheckInDate,
            'CheckOutDate' => $CheckOutDate,
            'Rooms'        => $Rooms
        ];

        if (!isset($Rooms['Room']) && !isset($Rooms['Room']['NumAdults']))
            throw new \InvalidArgumentException(
                'Invalid Rooms Params: '
                .'[\'Room\' => [\'NumAdults\' => 2, (optional) \'Children\' => [\'ChildAge\' => 4, 6] ] ]'
            );

        ($Nationality === null) ?: $params['Nationality'] = $Nationality;
        ($CityId      === null) ?: $params['CityId']      = $CityId;
        ($HotelId     === null) ?: $params['HotelId']     = $HotelId;
        ($Currency    === null) ?: $params['Currency']    = $Currency;

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

    /**
     * After a hotel search, an option can be selected to be booked.
     * Before making a booking for that option, its cancellation policies,
     * restrictions and important messages must be get using this method.
     *
     * @param integer $OptionId The id of the option that you want to get the policies of
     *
     * @return iResponse
     */
    function hotelPolicies($OptionId) {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [
            'OptionId' => $OptionId,
        ];

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    ) {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [
            'OptionId'      => $OptionId,
            'YourReference' => $YourReference,
            'Rooms'         => $Rooms
        ];

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    ) {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [];

        if (
            $BookingDates!==null
            && (!isset($BookingDates['BookingDateStart'])||!isset($BookingDates['BookingDateEnd']))
        )
            throw new \InvalidArgumentException(
                'Invalid BookingDates Params: '
                .'[\'BookingDateStart\'=>.., \'BookingDateEnd\'=>..]'
            );

        if (
            $CheckInDates!==null
            && (!isset($CheckInDates['CheckInDateStart'])||!isset($CheckInDates['CheckInDateEnd']))
        )
            throw new \InvalidArgumentException(
                'Invalid CheckInDates Params: '
                .'[\'CheckInDateStart\'=>.., \'CheckInDateEnd\'=>..]'
            );


        ($BookingReference === null) ?: $params['BookingReference'] = $BookingReference;
        ($YourReference    === null) ?: $params['YourReference']    = $YourReference;
        ($BookingDates     === null) ?: $params['BookingDates']     = $BookingDates;
        ($CheckInDates     === null) ?: $params['CheckInDates']     = $CheckInDates;

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

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
    function hotelBookingCancel($BookingReference)
    {
        $method = new ReqMethod(['method' => __FUNCTION__]);

        $params = [
            'BookingReference' => $BookingReference,
        ];

        (empty($params)) ?: $method->setArguments($params);

        return $this->call($method);
    }

    // Client Implementation:

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return iPlatform
     */
    function platform()
    {
        if (!$this->platform)
            $this->platform = new Platform($this);

        return $this->platform;
    }

    /**
     * Get Transporter Adapter
     *
     * @return iConnection
     */
    function transporter()
    {
        $mode = ($this->optsData()->isEnableFaker()) ? 'faker' : 'stream';

        if (!isset($this->transporter[$mode]))
            $this->transporter[$mode] = ($mode == 'faker') ? new FakerConnection : new HttpSocketConnection;

        return $this->transporter[$mode];
    }

    /**
     * @override
     * @inheritdoc
     *
     * @return iResponse
     */
    function call(iApiMethod $method)
    {
        if (!$method instanceof ReqMethod)
            $method = new ReqMethod([
                'method'    => $method->getMethod(),
                'arguments' => $method->getArguments(),
            ]);

        $method->setUsername($this->optsData()->getUsername());
        $method->setPassword($this->optsData()->getPassword());

        return parent::call($method);
    }

    // options:

    /**
     * @return HotelServiceOpts
     */
    function optsData()
    {
        if (!$this->options)
            $this->options = self::newOptsData();

        return $this->options;
    }

    /**
     * Get An Bare Options Instance
     *
     * ! it used on easy access to options instance
     *   before constructing class
     *   [php]
     *      $opt = Filesystem::optionsIns();
     *      $opt->setSomeOption('value');
     *
     *      $class = new Filesystem($opt);
     *   [/php]
     *
     * @param null|mixed $builder Builder Options as Constructor
     *
     * @return HotelServiceOpts
     */
    static function newOptsData($builder = null)
    {
        return (new HotelServiceOpts)->from($builder);
    }
}
