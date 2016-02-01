<?php
return [
    '/xmlv1/GetCountriesRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>GetCountries</ResponseType>
</Head>
<Body>
<CountriesReturned>249</CountriesReturned>
<Countries>
<Country>
<CountryCode>AF</CountryCode>
<CountryName>Afghanistan</CountryName>
</Country>
<Country>
<CountryCode>AX</CountryCode>
<CountryName>Åland Islands</CountryName>
</Country>
<Country>
<CountryCode>AL</CountryCode>
<CountryName>Albania</CountryName>
</Country>
<Country>
<CountryCode>DZ</CountryCode>
<CountryName>Algeria</CountryName>
</Country>
</Countries>
</Body>
</Response>',

    '/xmlv1/GetCitiesRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>GetCities</ResponseType>
</Head>
<Body>
<CitiesReturned>760</CitiesReturned>
<Countries>
<Country>
<CountryCode>GB</CountryCode>
<Cities>
<City>
<CityId>129552</CityId>
<CityName>Aberdeen</CityName>
</City>
<City>
<CityId>957079</CityId>
<CityName>Aberfeldy</CityName>
</City>
</Cities>
</Country>
<Country>
<CountryCode>US</CountryCode>
<Cities>
<City>
<CityId>986798</CityId>
<CityName>Abbeville</CityName>
<StateCode>AL</StateCode>
</City>
</Cities>
</Country>
</Countries>
</Body>
</Response>',
    '/xmlv1/GetHotelsRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>GetHotels</ResponseType>
</Head>
<Body>
<HotelsReturned>925</HotelsReturned>
<Hotels>
<Hotel>
<HotelId>1000452</HotelId>
<CityId>117976</CityId>
<HotelName>The Goring</HotelName>
</Hotel>
<Hotel>
<HotelId>1003381</HotelId>
<CityId>117976</CityId>
<HotelName>Mayflower Hotel Apartments</HotelName>
</Hotel>
</Hotels>
</Body>
</Response>',
    '/xmlv1/GetHotelDetailsRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>GetHotelDetails</ResponseType>
</Head>
<Body>
<HotelsReturned>250</HotelsReturned>
<Hotels>
<Hotel>
<HotelId>1009075</HotelId>
<CityId>117976</CityId>
<HotelName>Rocco Forte Brown\'s</HotelName>
<StarRating>5</StarRating>
<Latitude>51.50809</Latitude>
<Longitude>-0.14116</Longitude>
<Address>ALBEMARLE STREET W1S 4BP LONDON</Address>
<Location>Green Park</Location>
<PhoneNumber>0080076666667</PhoneNumber>
<Description>
This outstanding and award-winning hotel is situated in an idyllic location in the upmarket London
borough of Mayfair, just a stone\'s throw from Piccadilly Circus and the elegant shopping of Bond
Street and Regent Street. Numerous restaurants, bars, pubs, cafés, the West End theatre district and
world-famous attractions such as Buckingham Palace or Hyde Park are within just a short stroll.
</Description>
<Facilities>
<Facility>
<FacilityType>Hotel Facilities</FacilityType>
<FacilityName>Air-conditioned in common areas</FacilityName>
</Facility>
<Facility>
<FacilityType>Hotel Facilities</FacilityType>
<FacilityName>Reception area</FacilityName>
</Facility>
<Facility>
<FacilityType>Room Facilities</FacilityType>
<FacilityName>Satellite / cable TV</FacilityName>
</Facility>
</Facilities>
<Images>
<Image>
http://online.travellanda.com/photo/hotel/bf00b3d32a61153a/4f/4f8645de3dfe41ff/01-b.jpg
</Image>
<Image>
http://online.travellanda.com/photo/hotel/bf00b3d32a61153a/4f/4f8645de3dfe41ff/02-b.jpg
</Image>
</Images>
</Hotel>
</Hotels>
</Body>
</Response>',
    '/xmlv1/HotelSearchRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>HotelSearch</ResponseType>
</Head>
<Body>
<CityId>117976</CityId>
<CheckInDate>2013-12-18</CheckInDate>
<CheckOutDate>2013-12-20</CheckOutDate>
<Nationality>FR</Nationality>
<Currency>GBP</Currency>
<HotelsReturned>250</HotelsReturned>
<Hotels>
<Hotel>
<HotelId>1180324</HotelId>
<HotelName>Pullman London St Pancras</HotelName>
<StarRating>4</StarRating>
<Options>
<Option>
<OptionId>1453081951</OptionId>
<OnRequest>0</OnRequest>
<BoardType>Full Breakfast</BoardType>
<TotalPrice>612.87</TotalPrice>
<DiscountApplied>75.09</DiscountApplied>
<DealName>Minimum Night Discount Offer</DealName>
<Rooms>
<Room>
<RoomId>991098066-0</RoomId>
<RoomName>Superior Room</RoomName>
<NumAdults>2</NumAdults>
<NumChildren>0</NumChildren>
<RoomPrice>343.98</RoomPrice>
<DailyPrices>
<DailyPrice>171.99</DailyPrice>
<DailyPrice>171.99</DailyPrice>
</DailyPrices>
</Room>
<Room>
<RoomId>1953705460-0</RoomId>
<RoomName>Superior Room</RoomName>
<NumAdults>2</NumAdults>
<NumChildren>1</NumChildren>
<RoomPrice>343.98</RoomPrice>
<DailyPrices>
<DailyPrice>171.99</DailyPrice>
<DailyPrice>171.99</DailyPrice>
</DailyPrices>
</Room>
</Rooms>
</Option>
</Options>
</Hotel>
</Hotels>
</Body>
</Response>',
    '/xmlv1/HotelPoliciesRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>HotelPolicies</ResponseType>
</Head>
<Body>
<OptionId>1453081951</OptionId>
<Currency>GBP</Currency>
<CancellationDeadline>2013-12-13</CancellationDeadline>
<Policies>
<Policy>
<From>2013-12-14</From>
<Type>Amount</Type>
<Value>343.97</Value>
</Policy>
<Policy>
<From>2013-12-18</From>
<Type>Percentage</Type>
<Value>100</Value>
</Policy>
</Policies>
<Restrictions>
<Restriction>Name change</Restriction>
</Restrictions>
<Alerts>
<Alert>City tax applies, to be paid directly to the hotel upon check-in.</Alert>
</Alerts>
</Body>
</Response>',
    '/xmlv1/HotelBookingRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>HotelBooking</ResponseType>
</Head>
<Body>
<HotelBooking>
<BookingReference>TL12345678</BookingReference>
<BookingStatus>Confirmed</BookingStatus>
<YourReference>XMLTEST</YourReference>
<Currency>GBP</Currency>
<TotalPrice>612.87</TotalPrice>
</HotelBooking>
</Body>
</Response>',
    '/xmlv1/HotelBookingDetailsRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>HotelBookingDetails</ResponseType>
</Head>
<Body>
<BookingsFound>234</BookingsFound>
<BookingsReturned>100</BookingsReturned>
<Bookings>
<HotelBooking>
<BookingReference>TL12345678</BookingReference>
<BookingStatus>Confirmed</BookingStatus>
<PaymentStatus>Unpaid</PaymentStatus>
<BookingTime>2013-12-01T14:00:00</BookingTime>
<YourReference>XMLTEST</YourReference>
<Currency>GBP</Currency>
<TotalPrice>612.87</TotalPrice>
<HotelId>1180324</HotelId>
<HotelName>Pullman London St Pancras</HotelName>
<City>London, United Kingdom</City>
<CheckInDate>2013-12-18</CheckInDate>
<CheckOutDate>2013-12-20</CheckOutDate>
<LeaderName>First Name 1 Last Name 1</LeaderName>
<Nationality>FR</Nationality>
<BoardType>Full Breakfast</BoardType>
<CancellationDeadline>2013-12-13</CancellationDeadline>
<Rooms>
<Room>
<RoomName>Superior Room</RoomName>
<NumAdults>2</NumAdults>
<NumChildren>1</NumChildren>
</Room>
<Room>
<RoomName>Superior Room</RoomName>
<NumAdults>2</NumAdults>
<NumChildren>0</NumChildren>
</Room>
</Rooms>
<Policies>
<Policy>
<From>2013-12-14</From>
<Type>Amount</Type>
<Value>343.97</Value>
</Policy>
<Policy>
<From>2013-12-18</From>
<Type>Percentage</Type>
<Value>100</Value>
</Policy>
</Policies>
<Restrictions>
<Restriction>Name change</Restriction>
</Restrictions>
<Alerts>
<Alert>City tax applies, to be paid directly to the hotel upon check-in.</Alert>
</Alerts>
</HotelBooking>
</Bookings>
</Body>
</Response>',
    '/xmlv1/HotelBookingCancelRequest.xsd' =>
        '<Response>
<Head>
<ServerTime>2013-12-01T14:00:00</ServerTime>
<ResponseType>HotelBookingCancel</ResponseType>
</Head>
<Body>
<HotelBooking>
<BookingReference>TL12345678</BookingReference>
<BookingStatus>Cancelled</BookingStatus>
</HotelBooking>
</Body>
</Response>',

];