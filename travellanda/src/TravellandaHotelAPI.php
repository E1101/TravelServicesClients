<?php
namespace Ar\Travellanda;

class TravellandaHotelApi
{
    const PREFIX_KEY = 't_';
    
    private $snoopy;
    private $url = 'http://xmldemo.travellanda.com/xmlv1';
    private $username = '3222b42a21a8c237d23b4cf4c02de6c1';
    private $password = '61R2CNXF11Qh';
    private $_cachePrefix = 'travellanda_';
    
    function __construct()
    {
        $this->snoopy = new Snoopy;
        $this->snoopy->httpmethod = 'POST';
    }

    /**
     * Returns the list of all *items. Item id is mandatory for search service to get the list of available hotels.
     * @param type $query search string that you want to get hotel list or destination list
     */
    public function getItems($city)
    {
        $cacheKey = $this->_cachePrefix . 'items_' . $city;
        if (\Cache::has($cacheKey))
        {
            return \Cache::get($cacheKey);
        }
        
        $data['xml'] = "
            <Request>
                <Head>
                    <Username>" . $this->username . "</Username>
                    <Password>" . $this->password . "</Password>
                    <RequestType>GetHotels</RequestType>
                </Head>
                <Body>
                    <CityId>" . $this->getCity($city) . "</CityId>
                </Body>
            </Request>";
        $result = $this->asArray($this->snoopy->submit($this->url . '/GetHotelsRequest.xsd', $data)->results);
        $items = [];
        if (isset($result['Body']['Hotels']['Hotel']))
        {
            foreach ($result['Body']['Hotels']['Hotel'] as $item)
            {
                $items[$item['HotelId']] = ['cityId' => $item['CityId'], 'hotelName' => $item['HotelName']];
            }
            
            \Cache::put($cacheKey, $items, self::CACHE_ITEMS);
        }
        
        return $items;
    }

    /**
     * Returns the list of available rooms and their prices prior to the selected item id, date interval and guest count.
     * @param type $destionation that you want to get hotel list or destination list
     * @param type $nationality guest's nationality
     * @param type $checkInDate date for the begin of visit
     * @param type $checkOutDate date for the end of visit
     * @param type $rooms rooms info array including guest count [rooms[i].adultNum count of adult guest(s) per room, rooms.[i].childNum count of child guest(s) per room (Max childNum = 3)
     *      rooms.[i].child1age	age of first child, rooms.[i].child2age	age of second child, rooms.[i].child3age age of third child]
     * @param type $searchKey Search cache key.
     * @param type $currency currency code that prices will be converted.
     * @param type $structured return search result in Hotels Structure.
     */
    public function getRooms($destination, $nationality, $checkInDate, $checkOutDate, $rooms, $searchKey, $currency = null, $structured = true)
    {
       /* if (\Cache::has($this->generateSearchKey($searchKey)))
        {
            return \Cache::get($this->generateSearchKey($searchKey));
        }*/
        
        $roomsString = '';
        foreach ($rooms as $room)
        {
            $roomsString .= '<Room><NumAdults>' . $room['adultNum'] . '</NumAdults>';
            if ($room['childNum'] > 0)
            {
                unset($room['adultNum'], $room['childNum']);
                $roomsString .= '<Children>';
                foreach ($room as $child)
                {
                    $roomsString .= '<ChildAge>' . $child . '</ChildAge>';
                }
                $roomsString .= '</Children>';
            }
            $roomsString .= '</Room>';
        }
        
        $data['xml'] = '
            <Request>
                <Head>
                    <Username>' . $this->username . '</Username>
                    <Password>' . $this->password . '</Password>
                    <RequestType>HotelSearch</RequestType>
                </Head>
                <Body>
                    <CityId>' . $this->getCity($destination) . '</CityId>
                    <CheckInDate>' . $checkInDate . '</CheckInDate>
                    <CheckOutDate>' . $checkOutDate . '</CheckOutDate>
                    <Rooms>' . $roomsString . '</Rooms>						
                    <Nationality>' . $nationality . '</Nationality>
                    <Currency>' . $currency . '</Currency>
                </Body>
            </Request>';
        $result = $this->asArray($this->snoopy->submit($this->url . '/HotelSearchRequest.xsd', $data)->results);
        $searchResult = [];
        if (isset($result['Body']['Hotels']))
        {
            $searchResult['cityId'] = $result['Body']['CityId'];
            $searchResult['checkInDate'] = $result['Body']['CheckInDate'];
            $searchResult['checkOutDate'] = $result['Body']['CheckOutDate'];
            $searchResult['nationality'] = $result['Body']['Nationality'];
            $searchResult['currency'] = $result['Body']['Currency'];
            foreach ($result['Body']['Hotels']['Hotel'] as $hotel)
            {
                $searchResult['hotels'][$hotel['HotelId']] = $hotel;
            }
        }
        
        \Cache::put($this->generateSearchKey($searchKey), (($structured) ? $this->toStructure($searchResult, $destination) : $searchResult), self::CACHE_ITEMS);
        return \Cache::get($this->generateSearchKey($searchKey));
    }

    /**
     * Returns detailed price and available count information for selected room.
     * @param type $hotelId to receive detailed information for the hotel id you send.
     * @param type $rooms room ids.
     */
    public function getDetail($hotelId, $rooms)
    {
        $cacheKey =  'details_' . $hotelId;
        if (\Cache::has($cacheKey))
        {
            return \Cache::get($cacheKey);
        }
        
        $data['xml'] = '
            <Request>
                <Head>
                    <Username>' . $this->username . '</Username>
                    <Password>' . $this->password . '</Password>
                    <RequestType>GetHotelDetails</RequestType>
                </Head>
                <Body>
                    <HotelIds>
                        <HotelId>' . $hotelId . '</HotelId>
                    </HotelIds>	
                </Body>
            </Request>';
        $result = $this->asArray($this->snoopy->submit($this->url . '/GetHotelDetailsRequest.xsd', $data)->results);
        $details = null;
        if (isset($result['Body']['Hotels']))
        {
            foreach ($result['Body']['Hotels'] as $hotel)
            {
                $details = $hotel;
            }
            
            \Cache::put($cacheKey, $details, self::CACHE_DETAILS);
        }
        
        return $details;
    }
    
    /**
     * After a hotel search, an option can be selected to be booked. Before making a booking for
     * that option, its cancellation policies, restrictions and important messages must be get using the HotelPolicies method.
     * @param type $optionId selected option id.
     */
    public function getPolicies($optionId)
    {
        $cacheKey =  'policies_' . $optionId;
        /*if (\Cache::has($cacheKey))
        {
            return \Cache::get($cacheKey);
        }*/
        
        $data['xml'] = '
            <Request>
                <Head>
                    <Username>' . $this->username . '</Username>
                    <Password>' . $this->password . '</Password>
                    <RequestType>HotelPolicies</RequestType>
                </Head>
                <Body>
                    <OptionId>
                        ' . $optionId . '
                    </OptionId>	
                </Body>
            </Request>';
        $result = $this->asArray($this->snoopy->submit($this->url . '/HotelPoliciesRequest.xsd', $data)->results);
        $policies = [];
        $policies['CancellationDeadline'] = $result['Body']['CancellationDeadline'];
        $policies['Currency'] = $result['Body']['Currency'];
        $policies['Policies'] = $result['Body']['Policies'];
        $policies['Restrictions'] = $result['Body']['Restrictions'];
        $policies['Alerts'] = (is_array($result['Body']['Alerts']['Alert'])) ? $result['Body']['Alerts']['Alert'] : [$result['Body']['Alerts']['Alert']];
        \Cache::put($cacheKey, $policies, self::CACHE_DETAILS);
        
        return $policies;
    }

    /**
     * 
     * @param type $contactInfo represents the contact person(guest) info for the book.
     * @param type $peoples presents guest information array.
     * @param type $responseId responseId property of price detail service response payload.
     * @param type $notes agency's note for the book.
     */
    public function book($contactInfo, $peoples, $responseId, $notes = '')
    {
        $str = '{
            "contactInfo": ' . json_encode($contactInfo) . ',
            "people": ' . json_encode(array_values($peoples)) .',
            "responseId": "' . $responseId . '",
            "agencyNotes": "' . $notes . '"
        }';
        $url = $this->url . 'hotel/book/';
        $response = Common::curl($url . $this->authCode, $str, $this->headers);
        if (is_array($response) and isset($response['result']))
        {
            return $response['result'];
        }
        
        return [];
    }
    
    public function getCountries()
    {
        $cacheKey = $this->_cachePrefix . 'countries';
        if (\Cache::has($cacheKey))
        {
            return \Cache::get($cacheKey);
        }
        
        $snoopy = new Snoopy;
        $snoopy->httpmethod = "POST";
        $data['xml'] = "
            <Request>
                <Head>
                    <Username>" . $this->username . "</Username>
                    <Password>" . $this->password . "</Password>
                    <RequestType>GetCountries</RequestType>
                </Head>
                <Body/>
            </Request>";

        $result = $this->asArray($snoopy->submit($this->url . '/GetCountriesRequest.xsd', $data)->results);
        $countries = [];
        if (isset($result['Body']['Countries']['Country']))
        {
            foreach ($result['Body']['Countries']['Country'] as $value)
            {
                $countries[$value['CountryCode']] = $value['CountryName'];
            }
            
            \Cache::put($cacheKey, $countries, self::CACHE_DURATION);
        }
        
        return $countries;
    }
    
    public function getCities()
    {
        $cacheKey = $this->_cachePrefix . 'cities';
        if (\Cache::has($cacheKey))
        {
            return \Cache::get($cacheKey);
        }
        
        $snoopy = new Snoopy;
        $snoopy->httpmethod = "POST";
        $cities = [];
        foreach ($this->getCountries() as $countryCode => $countryName)
        {
            $data['xml'] = '
                <Request>
                    <Head>
                        <Username>' . $this->username . '</Username>
                        <Password>' . $this->password . '</Password>
                        <RequestType>GetCities</RequestType>
                    </Head>
                    <Body>
                        <CountryCode>' . $countryCode . '</CountryCode>
                    </Body>
                </Request>';
            $result = $this->asArray($snoopy->submit($this->url . '/GetCitiesRequest.xsd', $data)->results);
            if (isset($result['Body']['Countries']['Country']))
            {
                foreach ($result['Body']['Countries']['Country'] as $value)
                {
                    if (isset($value['City']))
                    {
                        foreach ($value['City'] as $city)
                        {
                            if (isset($city['CityId']))
                            {
                                $cities[$city['CityId']] = ucwords($city['CityName']);
                            }
                        }
                    }
                }

                \Cache::put($cacheKey, $cities, self::CACHE_DURATION);
            }
        }
        
        return $cities;
    }
    
    public function getCity($city)
    {
        return array_search(ucwords($city), $this->getCities());
    }
    
    public function getFromCache($searchKey)
    {
        if (\Cache::has($this->generateSearchKey($searchKey)))
        {
            return \Cache::get($this->generateSearchKey($searchKey));
        }
        
        return [];
    }
    
    private function generateSearchKey($searchKey)
    {
        return 'search_' . base64_encode($this->_cachePrefix . $searchKey);
    }
    
    private function asArray($data)
    {
        return json_decode(json_encode(simplexml_load_string($data)), true);
    }
    
    private function toStructure($data, $cityName)
    {
        $totalRooms = 0;
        ini_set('memory_limit','256M');
        $hotels = ['CityId' => $data['cityId'], 'ZoneId' => $data['cityId'],
            'CityName' => $cityName, 'Nationality' => $data['nationality'], 'ZoneName' => $cityName,
            'CheckInDate' => $data['checkInDate'], 'CheckOutDate' => $data['checkOutDate'],
            'Currency' => $data['currency']];
        $hotelData = [];
        $hotels['HotelResult'] = [];
        foreach ($data['hotels'] as $hotel)
        {
            //$details = $this->getDetail($hotel['HotelId']);
            foreach ($hotel['Options']['Option'] as $option)
            {
                if (!isset($option['OptionId']))
                {
                    $option = $hotel['Options']['Option'];
                }
                
                $board = ['OptionId' => $option['OptionId'], 'Board' => $option['BoardType'], 'OnRequest' => $option['OnRequest']];
                $packageKey = [];
                $gross = 0;
                foreach ($option['Rooms'] as $rooms)
                {
                    if (!isset($rooms['RoomId']))
                    {
                        foreach ($rooms as $room)
                        {
                            $board['HotelRooms'][] = $this->setOptionInfo($room, $gross, $packageKey);
                        }
                    }
                    else
                    {
                        $board['HotelRooms'][] = $this->setOptionInfo($rooms, $gross, $packageKey);
                    }
                }

                $board['Prices']['Price'] = ['Type' => 'S', 'Currency' => $data['currency'],
                    'TotalFixAmounts' => ['Gross' => $gross, 'DiscountAmount' => (isset($option['DiscountApplied'])) ? $option['DiscountApplied'] : 0]];
                $hotelData['HotelOptions'][] = $board;
                $hotelData['AdditionalElements']['PackageKey'] = base64_encode(static::PREFIX_KEY 
                        . $hotel['HotelId'] . '_'. $option['OptionId'] . '_' . implode('::', $packageKey));
                $hotelData['HotelInfo'] = ['HotelId' => $hotel['HotelId'], 'Name' => $hotel['HotelName'],
                    'StarRating' => $hotel['StarRating'], 'HotelCategory' => '', 'HotelType' => '',
                    'Description' => '',
                    'Address' => '',
                    'Images' => []];
                $totalRooms++;
            }

            if ($hotelData)
            {
                $hotels['HotelResult'][$hotel['HotelId']] = $hotelData;
                $hotelData = null;
            }
        }
        
        return ['result' => $hotels, 'totalRooms' => $totalRooms];
    }
    
    private function setOptionInfo($rooms, &$gross, &$packageKey)
    {
        $room = ['Units' => '', 'Source' => '', 'AvailRooms' => 0, 'RoomId' => $rooms['RoomId'],
            'Name' => $rooms['RoomName'], 'RoomCategory' => $rooms['RoomName'], 'NumAdults' => $rooms['NumAdults'],
            'NumChildren' => $rooms['NumChildren'], 'RoomPrice' => $rooms['RoomPrice'],
            'DailyPrices' => (isset($rooms['DailyPrices']['DailyPrice'])) ? $rooms['DailyPrices']['DailyPrice'] : []];
        
        $gross += (float) $rooms['RoomPrice'];
        $packageKey[] = $rooms['RoomId'];
        
        return $room;
    }
            
}